<?php

namespace App\Repository;

use App\Entity\Main\TenantDbConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TenantDbConfig>
 *
 * @method TenantDbConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method TenantDbConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method TenantDbConfig[]    findAll()
 * @method TenantDbConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenantDbConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TenantDbConfig::class);
    }

    public function save(TenantDbConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TenantDbConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TenantDbConfig[] Returns an array of TenantDbConfig objects
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

//    public function findOneBySomeField($value): ?TenantDbConfig
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
