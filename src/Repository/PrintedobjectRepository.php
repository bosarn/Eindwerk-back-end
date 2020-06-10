<?php

namespace App\Repository;

use App\Entity\Printedobject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Printedobject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Printedobject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Printedobject[]    findAll()
 * @method Printedobject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrintedobjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Printedobject::class);
    }

    /**
     * @param $query
     * @return Printedobject[] Returns an array of Printedobject objects
     */

    public function findBySearch($query)
    {

        $qb = $this-> createQueryBuilder( 'p');
        $qb
            -> where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('p.name', ':query'),
                        $qb->expr()->like('p.description', ':query')
                    )
                )
            )
            ->setParameter('query', '%'.$query.'%');
        return $qb
            ->getQuery()
            ->getResult();


    }


    /*
    public function findOneBySomeField($value): ?Printedobject
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;



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
}
