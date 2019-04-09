<?php

namespace App\Repository;

use App\Entity\CrptoTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CrptoTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrptoTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrptoTransaction[]    findAll()
 * @method CrptoTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrptoTransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrptoTransaction::class);
    }

    // /**
    //  * @return CrptoTransaction[] Returns an array of CrptoTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrptoTransaction
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
