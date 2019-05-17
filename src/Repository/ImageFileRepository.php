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

    /**
     * @param string $categoryName
     * @return mixed
     */
    public function getAllImageFilesInCategory($categoryName)
    {
        return $this->createQueryBuilder('i')
        ->addSelect('i')
        ->join('i.categories', 'c')
        ->where('c.categoryName = :categoryName')
        ->setParameter('categoryName', $categoryName)
        ->getQuery()
        ->getResult();
    }
}
