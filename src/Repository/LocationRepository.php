<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Location[] findByEmail(?string $email)

 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

//    /**
//     * @return Location[] Returns an array of Location objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Location
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findConflictingReservations($logement, $datedebut, $datefin, $currentLocationId = null)
{
    $queryBuilder = $this->createQueryBuilder('l')
        ->andWhere('l.logement = :logement')
        ->andWhere('l.datefin > :datedebut')
        ->andWhere('l.datedebut < :datefin')
        ->setParameter('logement', $logement)
        ->setParameter('datedebut', $datedebut)
        ->setParameter('datefin', $datefin);

    if ($currentLocationId) {
        $queryBuilder->andWhere('l.idlocation != :currentLocationId')
            ->setParameter('currentLocationId', $currentLocationId);
    }

    return $queryBuilder->getQuery()->getResult();
}
public function findByEmail(?string $email): array
{
    $qb = $this->createQueryBuilder('l')
        ->join('l.personne', 'personne'); // Use a more specific alias, like 'personne'

    if ($email !== null) {
        $qb->where('personne.email = :email')
           ->setParameter('email', $email);
    }

    return $qb->getQuery()->getResult();
}
}
