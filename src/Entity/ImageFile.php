<?php

namespace App\Entity;

use App\Service\UploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageFileRepository")
 */
class ImageFile
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFileName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="ImageFile")
     * @ORM\JoinTable(name="image_category")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $imageFileDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFileTitle;

    public function __construct()
    {
         $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(string $imageFileName): self
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addImageFile($this);
        }

        return $this;
    }

    public function removeCategory(Category $category)
    {
        if (!$this->categories->contains($category)) {
            return;
        }
        $this->categories->removeElement($category);
        $category->removeImageFile($this);
    }

    public function addCategories(Category $category)
    {
        if ($this->categories->contains($category)) {
            return;
        }
        $this->categories[] = $category;
        $category->addImageFile($this);
    }


    public function getImageFileDescription(): ?string
    {
        return $this->imageFileDescription;
    }

    public function setImageFileDescription(?string $imageFileDescription): self
    {
        $this->imageFileDescription = $imageFileDescription;

        return $this;
    }

    public function getImageFilePath()
    {
        return UploaderHelper::GALLERY . '/' . $this->getImageFileName();
    }

    public function getImageFileTitle(): ?string
    {
        return $this->imageFileTitle;
    }

    public function setImageFileTitle(string $imageFileTitle): self
    {
        $this->imageFileTitle = $imageFileTitle;

        return $this;
    }
}
