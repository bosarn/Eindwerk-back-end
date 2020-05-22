<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource(
 *     normalizationContext={"groups"={"category:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"category:write"}, "swagger_definition_name"="Write"},
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"object:read","category:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"object:read","category:write","category:read"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Printedobject::class, inversedBy="Categories")
     */
    private $Printedobject;


    public function __construct()
    {
        $this->Printedobject = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|Printedobject[]
     */
    public function getPrintedobject(): Collection
    {
        return $this->Printedobject;
    }

    public function addPrintedobject(Printedobject $printedobject): self
    {
        if (!$this->Printedobject->contains($printedobject)) {
            $this->Printedobject[] = $printedobject;
        }

        return $this;
    }

    public function removePrintedobject(Printedobject $printedobject): self
    {
        if ($this->Printedobject->contains($printedobject)) {
            $this->Printedobject->removeElement($printedobject);
        }

        return $this;
    }

}
