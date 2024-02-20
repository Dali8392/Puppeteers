<?php

namespace App\Repository;

use App\Entity\Voyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voyage>
 *
 * @method Voyage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voyage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voyage[]    findAll()
 * @method Voyage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyage::class);
    }

//    /**
//     * @return Voyage[] Returns an array of Voyage objects
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

//    public function findOneBySomeField($value): ?Voyage
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function SearchVoyageByDepDes($dep,$des) {
    $em=$this->getEntityManager ();
    if($dep === null){
       $sql="select v from App\Entity\Voyage v where v.destination LIKE :des ";
       $req=$em->createQuery($sql);
       $req->setParameter("des", $des ."%");
    }
    else if($des === null){
        $sql= "select v from App\Entity\Voyage v where v.depart LIKE :dep";
        $req=$em->createQuery($sql);
        $req->setParameter("dep", $dep . "%");
    }
    else {
        $sql= "select v from App\Entity\Voyage v where v.depart LIKE :dep AND v.destination LIKE :des";
        $req=$em->createQuery($sql);
        $req->setParameter("dep", $dep . "%");
        $req->setParameter("des", $des ."%");
    }
    $result=$req->getResult();
    return $result;

}


}
