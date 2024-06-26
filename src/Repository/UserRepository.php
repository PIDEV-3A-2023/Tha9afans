<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Query\AST\Functions\SimpleFunction;
use Doctrine\ORM\Query\AST\Functions\DateDiffFunction;
use Doctrine\ORM\Query\Lexer;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        // Register the DATEDIFF function with Doctrine
        $entityManager = $this->getEntityManager();
        $configuration = $entityManager->getConfiguration();
        $configuration->addCustomDatetimeFunction('DATEDIFF', DateDiffFunction::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }
    public function findByNomPrenomEmail($value): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like('u.nom', ':value'),
                $qb->expr()->like('u.prenom', ':value'),
                $qb->expr()->like('u.email', ':value')
            )
        )
            ->setParameter('value', '%'.$value.'%')
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10);

        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function searchByNomPrenomEmail($query)
    {
        return $this->createQueryBuilder('u')
            ->where('u.nom = :query')
            ->orWhere('u.prenom LIKE :query')
            ->orWhere('u.email LIKE :query')
            ->setParameter('query', $query)
            ->getQuery()
            ->getResult();
    }
    public function findAllOrderByCin(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.cin', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    public function findAllOrderByDateNaissance(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.datenaissance', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
    public function countByAdresse()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.adresse, COUNT(u.id) as count')
            ->groupBy('u.adresse');

        return $qb->getQuery()->getResult();
    }

    public function countByAgeAndGender()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u.datenaissance')
            ->addSelect("CASE WHEN DATEDIFF(CURRENT_DATE(), u.datenaissance) / 365 <= 18 THEN '<18' ELSE '>18' END as age")
            ->addSelect('u.genre as genre')
            ->addSelect('COUNT(u.id) as count')
            ->andWhere($qb->expr()->isNotNull('u.datenaissance'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('u.genre', "'homme'"),
                $qb->expr()->eq('u.genre', "'femme'"),
                $qb->expr()->eq('u.genre', "'autre'")
            ))
            ->groupBy('age')
            ->addGroupBy('genre');

        return $qb->getQuery()->getResult();
    }
    // UserRepository.php
    public function countByAgeAndGenderLessThan18()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u.datenaissance')
            ->addSelect("'<' as age")
            ->addSelect('u.genre as genre')
            ->addSelect('COUNT(u.id) as count')
            ->andWhere($qb->expr()->isNotNull('u.datenaissance'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('u.genre', "'homme'"),
                $qb->expr()->eq('u.genre', "'femme'"),
                $qb->expr()->eq('u.genre', "'autre'")
            ))
            ->andWhere($qb->expr()->gt('DATE_DIFF(CURRENT_DATE(), u.datenaissance)', '6570')) // 6570 days = 18 years
            ->groupBy('age')
            ->addGroupBy('genre');

        return $qb->getQuery()->getResult();
    }

    public function countByAgeAndGenderGreaterThan18()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u.datenaissance')
            ->addSelect("'>' as age")
            ->addSelect('u.genre as genre')
            ->addSelect('COUNT(u.id) as count')
            ->andWhere($qb->expr()->isNotNull('u.datenaissance'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('u.genre', "'homme'"),
                $qb->expr()->eq('u.genre', "'femme'"),
                $qb->expr()->eq('u.genre', "'autre'")
            ))
            ->andWhere($qb->expr()->lte('DATE_DIFF(CURRENT_DATE(), u.datenaissance)', '6570')) // 6570 days = 18 years
            ->groupBy('age')
            ->addGroupBy('genre');

        return $qb->getQuery()->getResult();
    }






//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
