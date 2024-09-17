<?php

namespace App\Repository;

use App\Entity\Tailler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tailler>
 *
 * @method Tailler|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tailler|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tailler[]    findAll()
 * @method Tailler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaillerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tailler::class);
    }

    // Définissez la méthode findAllWithDevis ici
        public function findAllWithDevis()
        {
            return $this->createQueryBuilder('t')
                ->leftJoin('t.devis', 'd')
                ->addSelect('d')
                ->getQuery()
                ->getResult();
        }

//    /**
//     * @return Tailler[] Returns an array of Tailler objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tailler
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
