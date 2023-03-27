<?php

namespace App\Test\Controller;

use App\Entity\Billet;
use App\Repository\BilletRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilletControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BilletRepository $repository;
    private string $path = '/billet/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Billet::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Billet index');

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
            'billet[code]' => 'Testing',
            'billet[dateValidite]' => 'Testing',
            'billet[prix]' => 'Testing',
            'billet[idEvenement]' => 'Testing',
        ]);

        self::assertResponseRedirects('/billet/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Billet();
        $fixture->setCode('My Title');
        $fixture->setDateValidite('My Title');
        $fixture->setPrix('My Title');
        $fixture->setIdEvenement('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Billet');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Billet();
        $fixture->setCode('My Title');
        $fixture->setDateValidite('My Title');
        $fixture->setPrix('My Title');
        $fixture->setIdEvenement('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'billet[code]' => 'Something New',
            'billet[dateValidite]' => 'Something New',
            'billet[prix]' => 'Something New',
            'billet[idEvenement]' => 'Something New',
        ]);

        self::assertResponseRedirects('/billet/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getDateValidite());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getIdEvenement());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Billet();
        $fixture->setCode('My Title');
        $fixture->setDateValidite('My Title');
        $fixture->setPrix('My Title');
        $fixture->setIdEvenement('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/billet/');
    }
}
