<?php

namespace App\Repository;

use App\Entity\Enregistrement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Enregistrement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enregistrement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enregistrement[]    findAll()
 * @method Enregistrement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnregistrementRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 1;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enregistrement::class);
    }




    /**
     * @return Enregistrement[] Returns an array of Enregistrement objects
     */

    public function getRegistrationPaginator($periode, int $offset)
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere("e.etat = 'ATTENTE'")
            ->andWhere('e.periode = :val2')
            ->setParameter('val2', $periode)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
//            ->getResult()
            ;
        return new Paginator($query);
    }




     /**
      * @return Enregistrement[] Returns an array of Enregistrement objects
      */

    public function findByCoubtryAndPeriode($pays,$periode)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.pays = :val')
            ->andWhere('e.periode = :val2')
            ->setParameter('val', $pays)
            ->setParameter('val2', $periode)
            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

     /**
      * @return Enregistrement[] Returns an array of Enregistrement objects
      */

    public function findByPeriodeAndValidation($periode)
    {
        return $this->createQueryBuilder('e')
            ->andWhere("e.etat = 'ATTENTE'")
            ->andWhere('e.periode = :val2')
            ->setParameter('val2', $periode)
            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAllRegistration($pays,$periode): ?array
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->select($qb->expr()->countDistinct('e.id'))
            ->andWhere('e.periode = :year')
            ->andWhere('e.pays = :val')
            ->setParameter('val', $pays)
            ->setParameter('year', $periode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function countStudentRegistration($pays,$periode): ?array
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->select($qb->expr()->countDistinct('e.id'))
            ->andWhere('e.periode = :year')
            ->andWhere('e.pays = :val')
            ->andWhere("e.profession = 'Etudiant'")
            ->setParameter('val', $pays)
            ->setParameter('year', $periode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function countWorkerRegistration($pays,$periode): ?array
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->select($qb->expr()->countDistinct('e.id'))
            ->andWhere('e.periode = :year')
            ->andWhere('e.pays = :val')
            ->andWhere("e.profession = 'Fonctionnaire'")
            ->setParameter('val', $pays)
            ->setParameter('year', $periode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function countChomeurRegistration($pays,$periode): ?array
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->select($qb->expr()->countDistinct('e.id'))
            ->andWhere('e.periode = :year')
            ->andWhere('e.pays = :val')
            ->andWhere("e.profession = 'Chômeur'")
            ->setParameter('val', $pays)
            ->setParameter('year', $periode)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Enregistrement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
