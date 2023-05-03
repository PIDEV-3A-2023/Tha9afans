<?php

namespace App\Repository;

use App\Entity\BilletReserver;
use App\Entity\Evenement;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BilletReserver>
 *
 * @method BilletReserver|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilletReserver|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilletReserver[]    findAll()
 * @method BilletReserver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilletReserverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilletReserver::class);
    }

    public function save(BilletReserver $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BilletReserver $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BilletReserver[] Returns an array of BilletReserver objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BilletReserver
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
