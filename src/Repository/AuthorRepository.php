<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function orderByUserName(){
        return $this->createQueryBuilder('a')->orderBy('a.username','ASC')->getQuery()->getResult();
    }


    public function searchByAlphabet(){
        //return $this->createQueryBuilder('a')->where('a.username LIKE :name')->setParameter('name','a%')->getQuery()->getResult();
        return $this->createQueryBuilder('a')->where('a.username LIKE :name')->setParameters(['name'=>'a%'],['email'=>'%m'])->getQuery()->getResult();
    
    }



    public function searchById($id){
        //return $this->createQueryBuilder('a')->where('a.username LIKE :name')->setParameter('name','a%')->getQuery()->getResult();
    return $this->createQueryBuilder('a')->join('a.book','b')->addSelect('b')->where('b.author =: id')->setParameter('id',$id)->getQuery()->getResult();
    }
    
    public function searchByUserName($username){
        return $this->createQueryBuilder('a')->where('a.username =: name')->setParameter('name',$username)->getQuery()->getResult();
    }
    
    
    public function minmax($min,$max){
        //return $this->createQueryBuilder('a')->where('a.username LIKE :name')->setParameter('name','a%')->getQuery()->getResult();
    return $this->createQuery('SELECT a FROM App\Entity\Author a where a.nb_books BETWEEN , 1 and max')->setParameters(['1'=>'min'],['max'=>'max'])
        ->getQuery()->getResult();
    
    }
    
    
    





















/*
    public function orderByemail(){
        return $this->createQueryBuilder('a')
        ->orderBy('a.email','ASC')
        ->getQuery()
        ->getResult();
    }
*/







   /* public function searchByref($ref){
        return $this->createQueryBuilder('a')->where('a.email =: email')->setParameter('email',$ref)->getQuery()->getResult();
    }
    */
    


   



    

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}