<?php

namespace App\Repository;

use App\Entity\GlobalUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GlobalUser>
 *
 * @method GlobalUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalUser[]    findAll()
 * @method GlobalUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalUser::class);
    }

//    /**
//     * @return GlobalUser[] Returns an array of GlobalUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GlobalUser
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
