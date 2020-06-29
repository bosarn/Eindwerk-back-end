<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostcodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"code:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"code:write"}, "swagger_definition_name"="Write"},
 *
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=PostcodeRepository::class)
 * @ApiFilter( SearchFilter::class, properties={"postcode": "partial"})
 */

class Postcode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write","code:read","code:write"})
     */
    private $gemeente;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write","code:read","code:write"})
     */
    private $plaatsnaam;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write","code:read","code:write"})
     */
    private $postcode;





    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:write","code:read","code:write"})
     */
    private $provincie;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGemeente(): ?string
    {
        return $this->gemeente;
    }

    public function setGemeente(string $gemeente): self
    {
        $this->gemeente = $gemeente;

        return $this;
    }
    public function getPlaatsnaam(): ?string
    {
        return $this->plaatsnaam;
    }

    public function setPlaatsnaam(string $plaatsnaam): self
    {
        $this->plaatsnaam = $plaatsnaam;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPostcode($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPostcode() === $this) {
                $user->setPostcode(null);
            }
        }

        return $this;
    }

    public function getProvincie(): ?string
    {
        return $this->provincie;
    }

    public function setProvincie(?string $provincie): self
    {
        $this->provincie = $provincie;

        return $this;
    }
}
