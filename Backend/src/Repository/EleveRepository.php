<?php

namespace App\Repository;

use App\Entity\Eleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eleve>
 */
class EleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleve::class);
    }

    // ✅ Méthode pour chercher les élèves avec filtres
    public function findByFilters(?string $eleve, ?string $niveau, ?string $groupe): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.groupes', 'g'); // ✅ Correction ici (ManyToMany = groupes)

        if ($eleve) {
            $qb->andWhere('e.nomEleve LIKE :eleve OR e.prenomEleve LIKE :eleve')
               ->setParameter('eleve', '%' . $eleve . '%');
        }

        if ($niveau) {
            $qb->andWhere('e.niveau = :niveau')
               ->setParameter('niveau', $niveau);
        }

        if ($groupe) {
            $qb->andWhere('g.nomGroupe = :groupe')
               ->setParameter('groupe', $groupe);
        }

        return $qb->getQuery()->getResult();
    }

    // ✅ Ajoute cette méthode pour récupérer les niveaux uniques (indispensable pour tes filtres)
    public function findDistinctNiveaux(): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('DISTINCT e.niveau')
            ->orderBy('e.niveau', 'ASC');

        return array_column($qb->getQuery()->getResult(), 'niveau');
    }
}

//    /**
//     * @return Eleve[] Returns an array of Eleve objects
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

//    public function findOneBySomeField($value): ?Eleve
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

