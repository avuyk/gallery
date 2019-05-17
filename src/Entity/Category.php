<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
    private $categoryName;

    /**
     * @ORM\ManyToMany(targetEntity="ImageFile", mappedBy="categories", fetch="EXTRA_LAZY")
     */
    private $ImageFile;

    public function __construct()
    {
        $this->ImageFile = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * @return Collection|ImageFile[]
     */
    public function getImageFile(): Collection
    {
        return $this->ImageFile;
    }

    public function addImageFile(ImageFile $imageFile): self
    {
        if (!$this->ImageFile->contains($imageFile)) {
            $this->ImageFile[] = $imageFile;
        }

        return $this;
    }

    public function removeImageFile(ImageFile $imageFile)
    {
        if (!$this->ImageFile->contains($imageFile)) {
            return;
        }
        $this->ImageFile->removeElement($imageFile);
        // not needed for persistence, just keeping both sides in sync
        $imageFile->removeCategory($this);
        return $this;
    }
}
