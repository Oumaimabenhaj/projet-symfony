<?php

namespace App\Repository;

use App\Entity\Ordonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordonnance>
 *
 * @method Ordonnance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordonnance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordonnance[]    findAll()
 * @method Ordonnance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonnance::class);
    }

   /**
     * @return Ordonnance[] Returns an array of Ordonnance objects
    */

    public function findByDate($date): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.dateprescription  = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
//    public function patient($id): array
//    {
//        return $this->createQueryBuilder('o')
//             ->join('o.patient','p')
//             ->addSelect('p')
//            ->andWhere('u.id = :val')
//            ->setParameter('val', $id)
//            ->orderBy('o.id', 'ASC')
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ordonnance
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
