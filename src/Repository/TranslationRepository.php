<?php

namespace App\Repository;

use App\Entity\Translation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Translation>
 *
 * @method Translation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Translation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Translation[]    findAll()
 * @method Translation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Translation::class);
    }

    public function save(Translation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Translation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Translation[] Returns an array of Translation objects
//     */
    public function findByKeyword($value): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.keyword = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findAvailableByKeyword($value, $entity): array
    {
        return $this->createQueryBuilder('t')
            ->join('t.' . $entity, 'e')
            ->where('t.keyword = :val')
            ->andWhere('e.available = true')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Translation
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
