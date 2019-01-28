<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="category")
     */
    private $videos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideo(): Collection
    {
        return $this->videos;
    }

    public function VideoAdd(Video $videos): self
    {
        if (!$this->videos->contains($videos)) {
            $this->videos[] = $videos;
            $videos->setCategory($this);
        }

        return $this;
    }

    public function VideoRemove(Video $videos): self
    {
        if ($this->videos->contains($videos)) {
            $this->videos->removeElement($videos);
            // set the owning side to null (unless already changed)
            if ($videos->getCategory() === $this) {
                $videos->setCategory(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
