<?php

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Security;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();

        $this->truncateEntities();
    }


    public function testItBuildsEnclosureWithDefaultSpecification()
    {

        $dinosaurFactory = $this->createMock(DinosaurFactory::class);

        $dinosaurFactory->expects($this->any())
            ->method('growFromSpecification')
            ->willReturnCallback(function ($spec){
                return new Dinosaur();
            });

        /** @var EnclosureBuilderService $enclosureBuilderService */
        $enclosureBuilderService = new EnclosureBuilderService(
            $this->getEntityManager(),
            $dinosaurFactory
        );

        $enclosureBuilderService->buildEnclosure();

        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $count = intval($em->getRepository(Security::class)
                    ->createQueryBuilder('s')
                    ->select('COUNT(s.id)')
                    ->getQuery()
                    ->getSingleScalarResult());

        $this->assertSame(1, $count, 'Amount of security systems is not the same');

        $count = intval($em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult());

        $this->assertSame(3, $count, 'Amount of dinosaurs  is not the same');


    }

    private function truncateEntities()
    {
        $purger =  new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return self::$kernel->getContainer()
                            ->get('doctrine')
                            ->getManager();
    }
}