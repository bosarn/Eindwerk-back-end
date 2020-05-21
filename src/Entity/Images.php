<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"image:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"image:write"}, "swagger_definition_name"="Write"},
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put","delete"},
 *     shortName="img"
 * )
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"object:read","object:write", "order:write","image:write","detail:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"object:read","object:write", "order:write","image:write","detail:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"object:read","object:write", "order:write","image:write","detail:write"})
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Printedobject::class, inversedBy="images")
     */
    private $printedobjects;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPrintedobjects(): ?Printedobject
    {
        return $this->printedobjects;
    }

    public function setPrintedobjects(?Printedobject $printedobjects): self
    {
        $this->printedobjects = $printedobjects;

        return $this;
    }

}
