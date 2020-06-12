<?php

namespace App\Repository;

use App\Entity\DiscountCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DiscountCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountCode[]    findAll()
 * @method DiscountCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountCode::class);
    }

    /**
     * @param string $code
     * @return DiscountCode|null
     * @throws \Exception
     */
    public function findByCode(string $code): ?DiscountCode
    {
        $qb = $this->createQueryBuilder('d')
            ->andWhere('d.code = :code')
            ->andWhere('d.expiryDate > :now')
            ->andWhere('d.usages < d.maxUsages')
            ->setParameter('code', $code)
            ->setParameter('now', (new \DateTime()))
        ;

        return $qb->getQuery()->getOneOrNullResult();
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
