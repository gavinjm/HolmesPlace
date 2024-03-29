<?php

namespace App\Repository;

use App\Entity\TollSlips;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
 

/**
 * @method TollSlips|null find($id, $lockMode = null, $lockVersion = null)
 * @method TollSlips|null findOneBy(array $criteria, array $orderBy = null)
 * @method TollSlips[]    findAll()
 * @method TollSlips[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TollSlipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TollSlips::class);
    }

    // /**
    //  * @return TollSlips[] Returns an array of TollSlips objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TollSlips
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
