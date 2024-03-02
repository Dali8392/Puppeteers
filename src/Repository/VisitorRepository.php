<?php

namespace App\Repository;

use App\Entity\Visitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visitor>
 *
 * @method Visitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visitor[]    findAll()
 * @method Visitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visitor::class);
    }

//    /**
//     * @return Visitor[] Returns an array of Visitor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Visitor
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByRegistrationDate(string $period): array
{
    $qb = $this->createQueryBuilder('u')
        ->where('u.visitDate >= :startDate')
        ->setParameter('startDate', $this->getStartDate($period));

    if ($period != 'all') {
        $qb->andWhere('u.visitDate <= :endDate')
           ->setParameter('endDate', new \DateTime());
    }

    return $qb->getQuery()->getResult();
}


private function getStartDate(string $period): \DateTime
{
    switch ($period) {
        case 'last_7_days':
            return new \DateTime('-7 days');
            break;
        case 'last_30_days':
            return new \DateTime('-30 days');
            break;
        default:
            return new \DateTime('2024-01-01'); // Mettez ici la date de début de votre choix
    }
}





}