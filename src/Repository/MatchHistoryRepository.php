<?php

namespace App\Repository;

use App\Entity\MatchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchHistory[]    findAll()
 * @method MatchHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchHistory::class);
    }

    // /**
    //  * @return MatchHistory[] Returns an array of MatchHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MatchHistory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
