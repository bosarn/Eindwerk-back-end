<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"order:read", "order:write"})
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
