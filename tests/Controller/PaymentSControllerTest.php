<?php

namespace App\Test\Controller;

use App\Entity\PaymentS;
use App\Repository\PaymentSRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentSControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PaymentSRepository $repository;
    private string $path = '/payment/s/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(PaymentS::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Payment index');

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
            'payment[cardNumber]' => 'Testing',
            'payment[cardHolder]' => 'Testing',
            'payment[cvv]' => 'Testing',
            'payment[date]' => 'Testing',
        ]);

        self::assertResponseRedirects('/payment/s/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new PaymentS();
        $fixture->setCardNumber('My Title');
        $fixture->setCardHolder('My Title');
        $fixture->setCvv('My Title');
        $fixture->setDate('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Payment');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new PaymentS();
        $fixture->setCardNumber('My Title');
        $fixture->setCardHolder('My Title');
        $fixture->setCvv('My Title');
        $fixture->setDate('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'payment[cardNumber]' => 'Something New',
            'payment[cardHolder]' => 'Something New',
            'payment[cvv]' => 'Something New',
            'payment[date]' => 'Something New',
        ]);

        self::assertResponseRedirects('/payment/s/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCardNumber());
        self::assertSame('Something New', $fixture[0]->getCardHolder());
        self::assertSame('Something New', $fixture[0]->getCvv());
        self::assertSame('Something New', $fixture[0]->getDate());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new PaymentS();
        $fixture->setCardNumber('My Title');
        $fixture->setCardHolder('My Title');
        $fixture->setCvv('My Title');
        $fixture->setDate('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/payment/s/');
    }
}
