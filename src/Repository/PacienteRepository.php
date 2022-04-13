<?php

namespace App\Repository;

use App\Entity\Paciente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Paciente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paciente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paciente[]    findAll()
 * @method Paciente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paciente::class);
    }

    // count all
    public function findAllCount(int $page, int $onPage): int
    {
        $query = $this->createQueryBuilder('p')->select('COUNT(p)')->getQuery();

        return $query->getResult();
    }

    // items by page
    public function findAllByPage(): array 
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.firstname', 'ASC')
            ->orderBy('p.lastname', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    // search nie
    public function findAllByNie($number): array 
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.nie LIKE :nie')
            //->andWhere('p.nie LIKE :nie OR p.mobile LIKE :nie')
            ->setParameter('nie', '%'.$number.'%')
            ->orderBy('p.firstname', 'ASC')
            ->orderBy('p.lastname', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    // one by nie
    public function findOneByNie(string $number): ?Paciente
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.nie = :number')
            ->setParameter('number', $number)
            ->getQuery();

        return $query->getOneOrNullResult();    
    }

    // search móvil
    public function findAllByMobile($number): array 
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.mobile LIKE :numb')
            ->setParameter('numb', '%'.$number.'%')
            ->orderBy('p.firstname', 'ASC')
            ->orderBy('p.lastname', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    // search Nombre
    public function findAllByFullname($text): array 
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.lastname LIKE :str')
            ->orWhere('p.firstname LIKE :str')
            ->setParameter('str', '%'.$text.'%')
            ->orderBy('p.firstname', 'ASC')
            ->orderBy('p.lastname', 'ASC')
            ->getQuery();

        return $query->getResult();
    }
}

