<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"file:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"file:write"}, "swagger_definition_name"="Write"},
 *     collectionOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "post"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=FilesRepository::class)
 */
class Files
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *@Groups({"detail:write","file:write","object:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"detail:write","order:read","file:write"})
     */
    private $GCODE;

    /**
     * @ORM\ManyToOne(targetEntity=Printedobject::class, inversedBy="Files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Printedobject;


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

    public function getGCODE(): ?string
    {
        return $this->GCODE;
    }

    public function setGCODE(string $GCODE): self
    {
        $this->GCODE = $GCODE;

        return $this;
    }

    public function getPrintedobject(): ?Printedobject
    {
        return $this->Printedobject;
    }

    public function setPrintedobject(?Printedobject $Printedobject): self
    {
        $this->Printedobject = $Printedobject;

        return $this;
    }

}
