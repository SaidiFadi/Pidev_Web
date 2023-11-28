<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }
//        /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findByTitreevt(string $titreevt): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('LOWER(e.titreevt) LIKE LOWER(:titreevt)')
        ->setParameter('titreevt', '%' . $titreevt . '%')
        ->getQuery()
        ->getResult();
}

public function findBySearchCriteria($searchCriteria)
{
    $queryBuilder = $this->createQueryBuilder('e');

    foreach ($searchCriteria as $field => $value) {
        if ($value !== null) {
            $queryBuilder
                ->andWhere("e.$field LIKE :$field")
                ->setParameter($field, '%' . $value . '%');
        }
    }

    return $queryBuilder->getQuery()->getResult();
}

  public function search(array $criteria): array
{
    $queryBuilder = $this->createQueryBuilder('e');

    foreach ($criteria as $field => $value) {
        if ($value !== null) {
            $condition = is_string($value) ? 'LIKE' : '=';
            $queryBuilder
                ->andWhere("e.$field $condition :$field")
                ->setParameter($field, is_string($value) ? "%$value%" : $value);
        }
    }

    return $queryBuilder
        ->getQuery()
        ->getResult();
}
// Example method in your repository
public function findByDateRange(\DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin): array
{
    $qb = $this->createQueryBuilder('e');
    $qb->where($qb->expr()->between('e.dateevt', ':dateDebut', ':dateFin'))
       ->setParameter('dateDebut', $dateDebut)
       ->setParameter('dateFin', $dateFin);

    return $qb->getQuery()->getResult();
}
public function topEventsByLikes($limit = 3)
{
    $qb = $this->createQueryBuilder('e')
        ->select('e.idevt, e.titreevt, COUNT(r.idBillet) AS likes')
        ->leftJoin('App\Entity\Reservation', 'r', 'WITH', 'r.idevt = e.idevt')
        ->where('r.vote = 1')
        ->groupBy('e.idevt')
        ->orderBy('likes', 'DESC')
        ->setMaxResults($limit);

    return $qb->getQuery()->getResult();
}

public function topEventsByParticipants($limit = 3)
{
    $qb = $this->createQueryBuilder('e')
    ->select('e.idevt, e.titreevt, COUNT(r.idBillet) AS nbParticipants')
        ->leftJoin('App\Entity\Reservation', 'r', 'WITH', 'r.idevt = e.idevt')
        ->groupBy('e.idevt')
        ->orderBy('nbParticipants', 'DESC')
        ->setMaxResults($limit);

    return $qb->getQuery()->getResult();
}

public function findEvenementsByUserId(int $id): array
{
    $qb = $this->createQueryBuilder('e');
    
    $qb->select('e')
       ->innerJoin('App\Entity\Reservation', 'r', 'WITH', 'e.idevt = r.idevt')
       ->where('r.id = :id')
       ->setParameter('id', $id);
    
    return $qb->getQuery()->getResult();
}

public function findByTimeRange(\DateTimeImmutable $hdEvt, \DateTimeImmutable $hfEvt)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.hdEvt >= :hdEvt')
            ->andWhere('e.hfEvt <= :hfEvt')
            ->setParameter('hdEvt', $hdEvt)
            ->setParameter('hfEvt', $hfEvt)
            ->getQuery()
            ->getResult();
    }
}
