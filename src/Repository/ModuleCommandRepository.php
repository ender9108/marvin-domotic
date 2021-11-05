<?php

namespace App\Repository;

use App\Entity\ModuleCommand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleCommand|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleCommand|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleCommand[]    findAll()
 * @method ModuleCommand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleCommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleCommand::class);
    }

    // /**
    //  * @return ModuleCommand[] Returns an array of ModuleCommand objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModuleCommand
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
