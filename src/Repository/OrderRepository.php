<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findByFiltered(Request $request): array
    {
        $statuses = $request->get('status');
        $qb = $this->createQueryBuilder('o');

        if ($statuses) {
            foreach ($statuses as $k => $status) {
                $qb->orWhere('o.status = :status' . $k)
                    ->setParameter('status' . $k, $status);
            }
        } else {
            $qb->orWhere('o.status = :available')
                ->setParameter('available', Order::STATUS_AVAILABLE);
            $qb->orWhere('o.status = :assigned')
                ->setParameter('assigned', Order::STATUS_TAKEN);
        }

        $qb->addOrderBy('o.status', 'ASC')
            ->addOrderBy('o.createdAt', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function findQueuedOrders(): array
    {
        $qb = $this->createQueryBuilder('o')
            ->orWhere('o.status = :available')
            ->setParameter('available', Order::STATUS_AVAILABLE)
            ->orWhere('o.status = :assigned')
            ->setParameter('assigned', Order::STATUS_TAKEN);

        return $qb->getQuery()->getResult();
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
