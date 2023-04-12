<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReservationRepository $repository;
    private string $path = '/reservation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

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
            'reservation[dateReservation]' => 'Testing',
            'reservation[status]' => 'Testing',
            'reservation[paymentInfo]' => 'Testing',
            'reservation[totalPrice]' => 'Testing',
            'reservation[paymentStatus]' => 'Testing',
            'reservation[nbrBillets]' => 'Testing',
            'reservation[nbrTicketType1Reserved]' => 'Testing',
            'reservation[nbrTicketType2Reserved]' => 'Testing',
            'reservation[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reservation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDateReservation('My Title');
        $fixture->setStatus('My Title');
        $fixture->setPaymentInfo('My Title');
        $fixture->setTotalPrice('My Title');
        $fixture->setPaymentStatus('My Title');
        $fixture->setNbrBillets('My Title');
        $fixture->setNbrTicketType1Reserved('My Title');
        $fixture->setNbrTicketType2Reserved('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDateReservation('My Title');
        $fixture->setStatus('My Title');
        $fixture->setPaymentInfo('My Title');
        $fixture->setTotalPrice('My Title');
        $fixture->setPaymentStatus('My Title');
        $fixture->setNbrBillets('My Title');
        $fixture->setNbrTicketType1Reserved('My Title');
        $fixture->setNbrTicketType2Reserved('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[dateReservation]' => 'Something New',
            'reservation[status]' => 'Something New',
            'reservation[paymentInfo]' => 'Something New',
            'reservation[totalPrice]' => 'Something New',
            'reservation[paymentStatus]' => 'Something New',
            'reservation[nbrBillets]' => 'Something New',
            'reservation[nbrTicketType1Reserved]' => 'Something New',
            'reservation[nbrTicketType2Reserved]' => 'Something New',
            'reservation[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateReservation());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getPaymentInfo());
        self::assertSame('Something New', $fixture[0]->getTotalPrice());
        self::assertSame('Something New', $fixture[0]->getPaymentStatus());
        self::assertSame('Something New', $fixture[0]->getNbrBillets());
        self::assertSame('Something New', $fixture[0]->getNbrTicketType1Reserved());
        self::assertSame('Something New', $fixture[0]->getNbrTicketType2Reserved());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reservation();
        $fixture->setDateReservation('My Title');
        $fixture->setStatus('My Title');
        $fixture->setPaymentInfo('My Title');
        $fixture->setTotalPrice('My Title');
        $fixture->setPaymentStatus('My Title');
        $fixture->setNbrBillets('My Title');
        $fixture->setNbrTicketType1Reserved('My Title');
        $fixture->setNbrTicketType2Reserved('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reservation/');
    }
}
