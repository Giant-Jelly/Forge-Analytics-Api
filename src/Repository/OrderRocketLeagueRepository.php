<?php

namespace App\Repository;

use App\Entity\OrderRocketLeague;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrderRocketLeague|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderRocketLeague|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderRocketLeague[]    findAll()
 * @method OrderRocketLeague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRocketLeagueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderRocketLeague::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
