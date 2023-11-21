<?php

namespace App\Repository;

use App\Entity\Logement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Logement>
 *
 * @method Logement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logement[]    findAll()
 * @method Logement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logement::class);
    }

//    /**
//     * @return Logement[] Returns an array of Logement objects
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

//    public function findOneBySomeField($value): ?Logement
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

  // LogementRepository.php
  public function findBySearchCriteria($searchCriteria)
  {
      $queryBuilder = $this->createQueryBuilder('l');
  
      foreach ($searchCriteria as $field => $value) {
          if ($value !== null) {
              $queryBuilder
                  ->andWhere("l.$field LIKE :$field")
                  ->setParameter($field, '%' . $value . '%');
          }
      }
  
      return $queryBuilder->getQuery()->getResult();
  }

public function search(array $criteria): array
{
    $queryBuilder = $this->createQueryBuilder('l');

    foreach ($criteria as $field => $value) {
        if ($value !== null) {
            $condition = is_string($value) ? 'LIKE' : '=';
            $queryBuilder
                ->andWhere("l.$field $condition :$field")
                ->setParameter($field, is_string($value) ? "%$value%" : $value);
        }
    }

    return $queryBuilder
        ->getQuery()
        ->getResult();
}
}
