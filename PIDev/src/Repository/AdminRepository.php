<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Admin>
 *
 * @method Admin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admin[]    findAll()
 * @method Admin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    public function findBySearchQuery(string $searchQuery):array
    {
        return $this->createQueryBuilder('e')
        ->where('e.nom LIKE :query')
        ->setParameter('query','%'. $searchQuery .'%')
        ->getQuery()
        ->getResult();
    }

    public function countAllAdmin(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id) as adminCount')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Admin[] Returns an array of Admin objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Admin
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
