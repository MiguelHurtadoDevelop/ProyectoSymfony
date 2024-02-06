<?php

namespace App\Repository;

use App\Entity\Pedidosproductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pedidosproductos>
 *
 * @method Pedidosproductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pedidosproductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pedidosproductos[]    findAll()
 * @method Pedidosproductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PedidosproductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pedidosproductos::class);
    }

//    /**
//     * @return Pedidosproductos[] Returns an array of Pedidosproductos objects
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

//    public function findOneBySomeField($value): ?Pedidosproductos
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
