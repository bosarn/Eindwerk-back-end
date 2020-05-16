<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PrintedobjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PrintedobjectRepository::class)
 */
class Printedobject
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
     * @ORM\Column(type="integer")
     */
    private $printTime;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $GCODE;

    /**
     * @ORM\OneToMany(targetEntity=Orderdetails::class, mappedBy="objects")
     */
    private $details;


    public function __construct()
    {
        $this->details = new ArrayCollection();
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

    public function getPrintTime(): ?int
    {
        return $this->printTime;
    }

    public function setPrintTime(int $printTime): self
    {
        $this->printTime = $printTime;

        return $this;
    }


    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

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

    /**
     * @return Collection|Orderdetails[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Orderdetails $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setObjects($this);
        }

        return $this;
    }

    public function removeDetail(Orderdetails $detail): self
    {
        if ($this->details->contains($detail)) {
            $this->details->removeElement($detail);
            // set the owning side to null (unless already changed)
            if ($detail->getObjects() === $this) {
                $detail->setObjects(null);
            }
        }

        return $this;
    }


}
