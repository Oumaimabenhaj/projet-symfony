<?php

namespace App\Repository;

use App\Entity\Dossiermedical;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @extends ServiceEntityRepository<Dossiermedical>
 *
 * @method Dossiermedical|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossiermedical|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossiermedical[]    findAll()
 * @method Dossiermedical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossiermedicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossiermedical::class);
    }

 /**
   * @return Dossiermedical[] Returns an array of Dossiermedical objects
    */
       public function findByPatientId($patientId): array
        {
            return $this->createQueryBuilder('d')
                ->andWhere('d.patient = :patientId')
               ->setParameter('patientId', $patientId)
              ->orderBy('d.datedecreation', 'ASC')
    
                  ->getQuery()
                  ->getResult();
                    
                   }

                   public function createDossierForPatient($patientId): ?Dossiermedical
                   {
                       $entityManager = $this->getEntityManager();
                
                       // Retrieve the Patient entity by its ID.
                       $patient = $entityManager->getRepository(Patient::class)->find($patientId);
                
                       if (!$patient) {
                           // Handle the case where the patient does not exist.
                           return null;
                       }
                
                       // Create a new Dossiermedical entity and associate it with the retrieved Patient.
                       $dossiers = new Dossiermedical();
                       $dossiers->setPatient($patient);
                
                       // Persist the new Dossiermedical entity.
                       $entityManager->persist($dossiers);
                       $entityManager->flush();
                
                       return $dossiers;
                   }
                   public function findByDate($date): array
                   {
                       return $this->createQueryBuilder('d')
                           ->andWhere('d.DateCreation = :date')
                           ->setParameter('date', $date)
                           ->getQuery()
                           ->getResult();
                   }
                   

                }
        
 


//    public function findOneBySomeField($value): ?Dossiermedical
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

