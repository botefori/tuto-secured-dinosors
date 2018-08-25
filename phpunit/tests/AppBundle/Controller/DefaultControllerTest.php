<?php

namespace Tests\AppBundle\Controller;


use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnHomePage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(3, $table->filter('tbody tr'));
    }

    /**
     *
     */
    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {

        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ])->getReferenceRepository();

        $enclosure = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%s', $enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }

    public function testItGrowsAdinosaurFromSpecification()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $client->followRedirects();

        $form = $crawler->selectButton('Grow dinosaur')->form();
        $form['enclosure']->select(3);
        $form['specification']->setValue('large herbivore');

        $client->submit($form);

        $this->assertContains(
            'Grew a large herbivore in enclosure #3',
            $client->getResponse()->getContent()
        );
    }

}