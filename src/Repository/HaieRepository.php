<?php

namespace App\Repository;

use App\Entity\Haie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Haie>
 *
 * @method Haie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Haie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Haie[]    findAll()
 * @method Haie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HaieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Haie::class);
    }

//    /**
//     * @return Haie[] Returns an array of Haie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Haie
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
