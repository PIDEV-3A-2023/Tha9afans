<?php

namespace App\Test\Controller;

use App\Entity\Stripep;
use App\Repository\StripepRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StripepControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private StripepRepository $repository;
    private string $path = '/stripep/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Stripep::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Stripep index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'stripep[card_number]' => 'Testing',
            'stripep[card_holder]' => 'Testing',
            'stripep[expiration_month]' => 'Testing',
            'stripep[expiration_year]' => 'Testing',
            'stripep[cvv]' => 'Testing',
        ]);

        self::assertResponseRedirects('/stripep/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stripep();
        $fixture->setCard_number('My Title');
        $fixture->setCard_holder('My Title');
        $fixture->setExpiration_month('My Title');
        $fixture->setExpiration_year('My Title');
        $fixture->setCvv('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Stripep');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stripep();
        $fixture->setCard_number('My Title');
        $fixture->setCard_holder('My Title');
        $fixture->setExpiration_month('My Title');
        $fixture->setExpiration_year('My Title');
        $fixture->setCvv('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'stripep[card_number]' => 'Something New',
            'stripep[card_holder]' => 'Something New',
            'stripep[expiration_month]' => 'Something New',
            'stripep[expiration_year]' => 'Something New',
            'stripep[cvv]' => 'Something New',
        ]);

        self::assertResponseRedirects('/stripep/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCard_number());
        self::assertSame('Something New', $fixture[0]->getCard_holder());
        self::assertSame('Something New', $fixture[0]->getExpiration_month());
        self::assertSame('Something New', $fixture[0]->getExpiration_year());
        self::assertSame('Something New', $fixture[0]->getCvv());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Stripep();
        $fixture->setCard_number('My Title');
        $fixture->setCard_holder('My Title');
        $fixture->setExpiration_month('My Title');
        $fixture->setExpiration_year('My Title');
        $fixture->setCvv('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/stripep/');
    }
}
