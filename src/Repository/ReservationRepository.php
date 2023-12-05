<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


// public function findReservationsByUserId($id)
//     {
//         return $this->createQueryBuilder('r')
//             ->join('r.titreevt', 'n')
//             ->where('r.id = :id')
//             ->setParameter('id', $id)
//             ->getQuery()
//             ->getResult();
//     }
    // Dans ReservationRepository.php


    public function countLikesAndDislikes(int $idevt): array
{
    $qb = $this->createQueryBuilder('r')
        ->select('SUM(CASE WHEN e.vote = 1 THEN 1 ELSE 0 END) AS likes')
        ->addSelect('SUM(CASE WHEN e.vote = 2 THEN 1 ELSE 0 END) AS dislikes')
        ->leftJoin('r.idevt', 'e') // Assuming your relation is named "evenement"
        ->where('e.idevt = :idevt')
        ->setParameter('idevt', $idevt)
        ->getQuery();

    return $qb->getScalarResult()[0];
}

    
public function findByUserId($id)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();
}

public function findEvenementsByUserId($id)
{
    return $this->createQueryBuilder('r')
        ->join('r.idevt', 'e')
        ->where('r.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();
}

}
