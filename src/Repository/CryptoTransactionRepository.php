<?php

namespace App\Repository;

use App\Entity\CryptoTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CryptoTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CryptoTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CryptoTransaction[]    findAll()
 * @method CryptoTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptoTransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CryptoTransaction::class);
    }

    // /**
    //  * @return CryptoTransaction[] Returns an array of CryptoTransaction objects
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
    public function findOneBySomeField($value): ?CryptoTransaction
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
