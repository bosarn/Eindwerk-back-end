<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PrintedobjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Doctrine\Common\Collections\Criteria;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"object:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"object:write"}, "swagger_definition_name"="Write"},
 *     collectionOperations={
 *         "get",
 *         "post"={},
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *       attributes={
 *          "pagination_items_per_page"=12,
 *         },
 *     shortName="objects",
 *
 * )
 * @ORM\Entity(repositoryClass=PrintedobjectRepository::class)
 * @ApiFilter( SearchFilter::class, properties={"name": "partial"}),
 * @ApiFilter(BooleanFilter::class , properties={"published"})
 *
 *
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
     * @ORM\Column(type="string", length=255 , nullable=true)
     * @Groups({"object:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     *@Groups({"object:write","object:read"})
     */
    private $printTime;


    /**
     * @ORM\Column(type="string", nullable=true)
     *@Groups({"object:read", "object:write"})
     */
    private $size;


    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="objects")
     *
     */
    private $details;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="Printedobject",cascade={"remove"})
     * @Groups({"object:read","order:write","object:write"})
     */
    private $Categories;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="Printedobject",cascade={"remove"})
     * @Groups({"object:read"})
     * Object read to find out how many files in JS
     */
    private $Files;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="printedobjects",cascade={"remove"})
     * @Groups({"detail:read","object:read", "object:write"})
     *
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Price::class, mappedBy="Printedobject",cascade={"remove"})
     *@Groups({"object:read","object:write"})
     */
    private $Price;

    /**
     * @ORM\Column(type="string", length=1200, nullable=true)
     * @Groups({"object:read","object:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"object:read","object:write"})
     */
    private $published;


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


    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
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
public function getCurrentPrice() {

        $criteria = Criteria::create()
            ->where(Criteria::expr()->lte('pricestart', new \DateTime()))
            -> orderBy( array('pricestart' => Criteria::DESC))
            -> setMaxResults(1);
        return $this->getPrice()->matching($criteria);
}

    /**
     * @return string
     * @Groups({"detail:read","object:read"})
     */

public function getCurrentPriceValue () {
      $prices =  $this->getCurrentPrice();
    $global = '';
    foreach($prices as $price) {
        $global = $price->getValue();
    }
    return $global;

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

public function getPublished(): ?bool
{
    return $this->published;
}

public function setPublished(?bool $published): self
{
    $this->published = $published;

    return $this;
}

public function getFormattedPrice() {
    $price = $this->getCurrentPriceValue();
    $last2characters = substr(strval($price), -2);
    $firstcharacters = substr(strval($price), 0, -2);



    return  '€'.$firstcharacters . ',' . $last2characters;
}


}
