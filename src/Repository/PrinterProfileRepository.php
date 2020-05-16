<?php

namespace App\Repository;

use App\Entity\PrinterProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrinterProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrinterProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrinterProfile[]    findAll()
 * @method PrinterProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrinterProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrinterProfile::class);
    }

    // /**
    //  * @return PrinterProfile[] Returns an array of PrinterProfile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrinterProfile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
