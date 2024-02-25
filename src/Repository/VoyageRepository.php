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
    if($dep === null || $dep==="" ){
        if (strpos(strtolower($des),",") !== false) {
            $sql= "select v from App\Entity\Voyage v where v.destination LIKE :des";
            $req=$em->createQuery($sql);
             $req->setParameter("des", trim(explode(',',$des)[0])."%");}
             else{
                $sql="select v from App\Entity\Voyage v where v.destination LIKE :des ";
                $req=$em->createQuery($sql);
                $req->setParameter("des", "%".$des ."%");}
    }
    else if($des === null || $des==="" ){
        if (strpos(strtolower($dep),",") !== false) {
            $sql= "select v from App\Entity\Voyage v where v.depart LIKE :dep";
            $req=$em->createQuery($sql);
             $req->setParameter("dep", trim(explode(',',$dep)[0])."%");}
         else{
            $sql= "select v from App\Entity\Voyage v where v.depart LIKE :dep";
            $req=$em->createQuery($sql);
            $req->setParameter("dep", "%".$dep . "%");}
        
    }
    else {
        $sql= "select v from App\Entity\Voyage v where v.depart LIKE :dep AND v.destination LIKE :des";
        $req=$em->createQuery($sql);
        if (strpos(strtolower($des),",") !== false) {
            $req->setParameter("des", trim(explode(',',$des)[0])."%");}
            else { $req->setParameter("des", "%".$des ."%");} 
        
        if (strpos(strtolower($dep),",") !== false) {
            $req->setParameter("dep", trim(explode(',',$dep)[0])."%");}
            else { $req->setParameter("dep", "%".$dep ."%");} 
       
    }
    $result=$req->getResult();
    return $result;

}


public function SearchVoyageByTransport($transport){
    return $this->createQueryBuilder('v')
            ->andWhere('v.moyenTransport = :transport')
            ->setParameter('transport', $transport)
            ->getQuery()
            ->getResult();
}


public function FilterVoyages($dep,$des,$datedep,$budget) {
    $em = $this->getEntityManager();
    $qb = $em->createQueryBuilder();

    $qb->select('v')
        ->from('App\Entity\Voyage', 'v');

    if (!empty($dep)) {
        if (strpos(strtolower($dep),",") !== false) {
        $qb->andWhere('v.depart LIKE :dep')
            ->setParameter('dep', trim(explode(',',$dep)[0]) . '%');
        }
        else{
            $qb->andWhere('v.depart LIKE :dep')
            ->setParameter('dep', "%".$dep . '%');
        }
    }

    if (!empty($des)) {
        if (strpos(strtolower($des),",") !== false) {
            $qb->andWhere('v.destination LIKE :des')
                ->setParameter('des', trim(explode(',',$des)[0]) . '%');
            }
            else{
                $qb->andWhere('v.destination LIKE :des')
                ->setParameter('des', '%' . $des . '%');
            }
      
    }

    if (!empty($datedep)) {
        $qb->andWhere('v.DateDep = :datedep')
            ->setParameter('datedep', $datedep);
    }

    if (!empty($budget) && $budget > 0) {
        $qb->andWhere('v.prix <= :budget')
            ->setParameter('budget', $budget);
    }

    $query = $qb->getQuery();
    $result = $query->getResult();

    return $result;



}

}
