<?php

namespace App\Repository;

use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Doctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctor[]    findAll()
 * @method Doctor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    // for paging
    public function findAllByPage(int $page = 1, int $onPage = 10): array 
    {
        $query = $this->createQueryBuilder('d')
                    //->where('d.is_active = 1')
                    ->orderBy('d.firstname', 'ASC')
                    ->orderBy('d.lastname', 'ASC')
                    ->setFirstResult($onPage * ($page - 1))
                    ->setMaxResults($onPage)
                    ->getQuery();

        return $query->getResult(Query::HYDRATE_SIMPLEOBJECT);    
    }

    // count for paging
    public function findAllCount()
    {
        $query = $this->createQueryBuilder('d')->select('COUNT(d)')->getQuery();

        return $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    // type 1
    public function findAllActives(): array 
    {
        $em = $this->getEntityManager();    
        $query = $em->createQuery("
            SELECT d
            FROM App\Entity\Doctor d 
            WHERE d.is_active = 1 
            ORDER BY d.lastname, d.firstname ASC 
        ");

        return $query->getResult();
    }

    // type 2
    public function findAllActivesss() 
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.is_active = 1')
            ->orderBy('d.firstname', 'DESC')
            ->orderBy('d.lastname', 'DESC');
            //->setMaxResults(1);
        $query = $qb->getQuery();
        
        return $query->getResult();
    }

    // 
    public function findAllHaveYearsMas1()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.years > 1')
            ->orderBy('d.lastname', 'ASC')
            ->orderBy('d.firstname', 'ASC')
            //->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Doctor[] Returns an array of Doctor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Doctor
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

