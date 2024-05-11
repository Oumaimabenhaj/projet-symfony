<?php

namespace App\Repository;

use App\Entity\Medicament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medicament>
 *
 * @method Medicament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medicament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medicament[]    findAll()
 * @method Medicament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicament::class);
    }

public function getMedicamentStats()
{
    return $this->createQueryBuilder('m')
        ->select('m.etat, COUNT(m.id) AS nombreMedicament')
        ->groupBy('m.etat')
        ->getQuery()
        ->getResult();
}
public function getMedicamentStats1()
{
    return $this->createQueryBuilder('m')
        ->select('m.nom_med, SUM(m.Qte) as totalQte') // Utilisez SUM pour obtenir la somme de la quantité
        ->groupBy('m.nom_med')
        ->getQuery()
        ->getResult();
}
public function getCategorieStats()
{
        
    return $this->createQueryBuilder('m')
    ->select('c.nom_cat AS categorie, COUNT(m.id) AS nombreMedicament')
    ->leftJoin('m.categorie', 'c') // Utilisez leftJoin pour inclure les Medicaments sans categorie
    ->groupBy('c.id')
    ->getQuery()
    ->getResult();
}
public function getTop5ExpiringMedicaments()
{
    return $this->createQueryBuilder('m')
        ->where('m.date_expiration >= CURRENT_DATE()') // Filtre pour inclure uniquement les médicaments non expirés
        ->orderBy('m.date_expiration', 'ASC') // Triez par date d'expiration croissante
        ->setMaxResults(5) // Limitez les résultats à 5
        ->getQuery()
        ->getResult();
}
}