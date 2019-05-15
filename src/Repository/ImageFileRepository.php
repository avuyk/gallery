<?php

namespace App\Repository;

use App\Entity\ImageFile;
use App\Exception\CouldNotSaveImageFileException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ImageFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageFile[]    findAll()
 * @method ImageFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ImageFile::class);
    }

    /**
     * @param ImageFile $imageFile
     * @throws CouldNotSaveImageFileException
     */
    public function save(ImageFile $imageFile): void
    {
        try {
            $this->getEntityManager()->persist($imageFile);
            $this->getEntityManager()->flush();
        } catch (ORMException $exception) {
            throw CouldNotSaveImageFileException::forError($exception);
        }
    }

    /**
     * @param string|null $term
     * @return QueryBuilder
     */
    public function getAllOrderedByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c')
            ->orderBy('c.updatedAt', 'DESC')
            ;
    }
    // /**
    //  * @return File[] Returns an array of File objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?File
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
