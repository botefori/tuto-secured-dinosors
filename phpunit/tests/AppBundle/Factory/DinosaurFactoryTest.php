<?php

namespace Tests\AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{

    /** @var DinosaurFactory */
    private $factory;


    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $lengthDeterminator;

    protected function setUp()
    {
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }


    public function testItGrowsAVelociraptor()
    {

        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertInternalType('string', $dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }

    public function testItGrowsATriceraptor()
    {
       $this->markTestIncomplete('Wainting for confirmation from GenLab');
    }

    public function testItGrowsABabyVelociraptor()
    {
        if(!class_exists('Nany'))
        {
            $this->markTestSkipped('There is nobody to watch the baby ..');
        }
        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());
    }

    /**
     * @dataProvider getSpecificationTest
     */
    public function testItGrowsADinosaurFromASpecification(string $spec, bool $expectedIsCarnivorous)
    {
        $this->lengthDeterminator->method('getLengthFromSpecification')
                                 ->willReturn(20);
        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Diets do not match');
        $this->assertSame(20, $dinosaur->getLength());
    }

    public function getSpecificationTest()
    {
        return [
            // specification, is large, is carnivorous
            ['large carnivorous dinosaur',  true],
            'default response' => ['give me all the cookies !!!!!', false],
            ['large herbivore',  false],
        ];
    }


}