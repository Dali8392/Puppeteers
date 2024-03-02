<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function findByRegistrationDate(string $period): array
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.dateInscri >= :startDate')
            ->setParameter('startDate', $this->getStartDate($period));
    
        if ($period != 'all') {
            $qb->andWhere('u.dateInscri <= :endDate')
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

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}