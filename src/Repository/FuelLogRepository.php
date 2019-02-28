<?php

namespace App\Repository;

use App\Entity\FuelLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FuelLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method FuelLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method FuelLog[]    findAll()
 * @method FuelLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FuelLog::class);
    }

    // /**
    //  * @return FuelLog[] Returns an array of FuelLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FuelLog
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
