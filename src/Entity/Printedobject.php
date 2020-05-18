<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PrintedobjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"object:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"object:write"}, "swagger_definition_name"="Write"},
 * )
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
     * @Groups({"order:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"order:read", "order:write"})
     */
    private $printTime;


    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"object:read","order:read", "order:write"})
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"object:read","order:read", "order:write"})
     */
    private $GCODE;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="objects")
     * @Groups({"detail:read"})
     */
    private $details;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="Printedobject")
     * @Groups({"object:read","order:read", "order:write"})
     */
    private $Categories;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="Printedobject")
     * @Groups({"object:read","order:read", "order:write"})
     */
    private $Files;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="printedobjects")
     * @Groups({"object:read","order:read", "order:write"})
     *
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Price::class, mappedBy="Printedobject")
     */
    private $Price;


    public function __construct()
    {
        $this->details = new ArrayCollection();
        $this->Categories = new ArrayCollection();
        $this->Files = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->Price = new ArrayCollection();
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

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addPrintedobject(Category $category): self
    {
        if (!$this->Categories->contains($category)) {
            $this->Categories[] = $category;
            $category->addPrintedobject($this);
        }

        return $this;
    }

    public function removePrintedobject(Category $printedobject): self
    {
        if ($this->Categories->contains($printedobject)) {
            $this->Categories->removeElement($printedobject);
            $printedobject->removePrintedobject($this);
        }

        return $this;
    }

    /**
     * @return Collection|Files[]
     */
    public function getFiles(): Collection
    {
        return $this->Files;
    }

    public function addFile(Files $file): self
    {
        if (!$this->Files->contains($file)) {
            $this->Files[] = $file;
            $file->setPrintedobject($this);
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->Files->contains($file)) {
            $this->Files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getPrintedobject() === $this) {
                $file->setPrintedobject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPrintedobjects($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getPrintedobjects() === $this) {
                $image->setPrintedobjects(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Price[]
     */
    public function getPrice(): Collection
    {
        return $this->Price;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->Price->contains($price)) {
            $this->Price[] = $price;
            $price->setPrintedobject($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->Price->contains($price)) {
            $this->Price->removeElement($price);
            // set the owning side to null (unless already changed)
            if ($price->getPrintedobject() === $this) {
                $price->setPrintedobject(null);
            }
        }

        return $this;
    }


}
