<?php

namespace App\Repository;

use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupe>
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    public function findByFilters($nomGroupe, $niveau, $typeGroupe, $eleve)
    {
        $qb = $this->createQueryBuilder('g')
            ->leftJoin('g.salle', 's')
            ->leftJoin('g.prof', 'p')
            ->leftJoin('g.eleves', 'e'); // jointure avec les élèves
    
        if ($nomGroupe) {
            $qb->andWhere('g.nomGroupe LIKE :nomGroupe')
               ->setParameter('nomGroupe', '%' . $nomGroupe . '%');
        }
    
        if ($niveau) {
            $qb->andWhere('g.niveauGroupe = :niveau')
               ->setParameter('niveau', $niveau);
        }
    
        if ($typeGroupe) {
            $qb->andWhere('g.typeGroupe = :typeGroupe')
               ->setParameter('typeGroupe', $typeGroupe);
        }
    
        if ($eleve) {
            $qb->andWhere('e.nomEleve LIKE :eleve OR e.prenomEleve LIKE :eleve')
               ->setParameter('eleve', '%' . $eleve . '%');
        }
    
        return $qb->getQuery()->getResult();
    }
    

// Pour récupérer les niveaux uniques
public function findDistinctNiveaux(): array
{
    $qb = $this->createQueryBuilder('g')
        ->select('DISTINCT g.niveauGroupe')
        ->orderBy('g.niveauGroupe', 'ASC');
    
    return array_column($qb->getQuery()->getResult(), 'niveauGroupe');
}

// Pour récupérer les types de groupe uniques
public function findDistinctTypesGroupes(): array
{
    $qb = $this->createQueryBuilder('g')
        ->select('DISTINCT g.typeGroupe')
        ->orderBy('g.typeGroupe', 'ASC');
    
    return array_column($qb->getQuery()->getResult(), 'typeGroupe');
}

public function findDistinctNomGroupes(): array
{
    $qb = $this->createQueryBuilder('g')
        ->select('DISTINCT g.nomGroupe')
        ->orderBy('g.nomGroupe', 'ASC');

    return array_column($qb->getQuery()->getResult(), 'nomGroupe');
}




//    /**
//     * @return Groupe[] Returns an array of Groupe objects
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

//    public function findOneBySomeField($value): ?Groupe
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
