<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $email;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="user")
     */
    private $videos;

    /**
     *  @ORM\Column(type="simple_array") *
     */
    private $roles;

    /**
     * @ORM\Column()
     */
    private $password;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->roles = array('ROLE_USER');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
            $videos->setUser($this);
        }

        return $this;
    }

    public function VideoRemove(Video $videos): self
    {
        if ($this->videos->contains($videos)) {
            $this->videos->removeElement($videos);
            // set the owning side to null (unless already changed)
            if ($videos->getUser() === $this) {
                $videos->setUser(null);
            }
        }

        return $this;
    }

}
