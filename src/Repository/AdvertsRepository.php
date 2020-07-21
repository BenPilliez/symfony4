<?php

namespace App\Repository;

use App\Entity\Adverts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adverts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adverts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adverts[]    findAll()
 * @method Adverts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adverts::class);
    }

    public function myFindAll()
    {
        return $this->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
    }

    public function myfindOne($id)
    {
        return $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByAythorAndDate($author, $year)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where('a.author = :author')
            ->setParameter('author', $author)
            ->andWhere('a.date < :year')
            ->setParameter('date', $year)
            ->orderBy('a.date', 'DESC');

        return $qb->getQuery()
            ->getResult();
    }

    public function myFind()
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where('a.author = :author')
        ->setParameter('author', 'Benjamin');

        //On applique notre condition sur notre QueryBuilder
        $this->whereCurrentYear($qb);

        $qb->orderBy('a.date','DESC');

        return $qb->getQuery()
        ->getResult();

    }

    // Fonction pour ajouter une condition sur l'année en cours
    public function whereCurrentYear(QueryBuilder $qb)
    {
      $qb
        ->andWhere('a.date BETWEEN :start AND :end')
        ->setParameter('start', new \Datetime(date('Y').'-01-01'))  // Date entre le 1er janvier de cette année
        ->setParameter('end',   new \Datetime(date('Y').'-12-31'))  // Et le 31 décembre de cette année
      ;
    }

    public function getAdvertWithApplications(){
        $qb = $this->createQueryBuilder('a')
        ->leftJoin('a.application', 'app')
        ->addSelect('app');

        return $qb->getQuery()
        ->getResult();
    }

    public function getAdvertWithCategories(array $categories)
    {
        $qb = $this->createQueryBuilder('a')
        ->innerJoin('a.categories', 'c')
        ->addSelect('c');

        $qb->where($qb->expr()->in('c.name', $categories));

        return $qb->getQuery()
        ->getResult();
    }
}
