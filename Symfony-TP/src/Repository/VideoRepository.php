<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Video::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByDate()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('v')
            ->andWhere('v.published = 1')
            ->andWhere('v.createdAt <= :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function findByDateSup()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('v')
            ->andWhere('v.published = 0')
            ->orWhere('v.createdAt > :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function countVideo($email)
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v)')
            ->join('v.user', 'u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUserVideo($email)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.user', 'u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

    public function getVideoUrl()
    {
        return $this->createQueryBuilder('v')
            ->select('v.url')
            ->getQuery()
            ->getResult();
    }

    public function getCategoryVideo($id)
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('v')
            ->select('v')
            ->join('v.category', 'c')
            ->where('c.id = :category_id')
            ->andWhere('v.createdAt <= :now')
            ->setParameters(array('category_id'=> $id, 'now' => $now))
            ->andWhere('v.published = 1')
            ->getQuery()
            ->getResult();
    }
}
