<?php

namespace App\Repository;

use App\Entity\PlatformBundle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlatformBundle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatformBundle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatformBundle[]    findAll()
 * @method PlatformBundle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatformBundleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlatformBundle::class);
    }

    // /**
    //  * @return PlatformBundle[] Returns an array of PlatformBundle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlatformBundle
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
