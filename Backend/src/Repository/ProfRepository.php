<?php

namespace App\Repository;

use App\Entity\Prof;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prof>
 */
class ProfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prof::class);
    }

    public function findByFilters(?string $prof, ?string $centre): array
{
    $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.centres', 'c') // ✅ Jointure ManyToMany
        ->addSelect('c'); // ✅ Important pour charger les centres

    if ($prof) {
        $qb->andWhere('p.nomProf LIKE :prof OR p.prenomProf LIKE :prof')
           ->setParameter('prof', '%' . $prof . '%');
    }

    if ($centre) {
        $qb->andWhere('c.nomCentre = :centre') // ✅ Filtre sur le nom du centre
           ->setParameter('centre', $centre);
    }

    return $qb->getQuery()->getResult();
}


//    /**
//     * @return Prof[] Returns an array of Prof objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Prof
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
