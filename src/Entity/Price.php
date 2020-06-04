<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource(
 *     normalizationContext={"groups"={"price:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"price:write"}, "swagger_definition_name"="Write"},
 *     collectionOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "post"={},
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass=PriceRepository::class)
 */
// todo Calculate price by weight && printtime
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"order:read","object:read","object:write", "detail:write","order:write","price:write"})
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read","object:read", "detail:write","order:write","price:write"})
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity=Printedobject::class, inversedBy="Price")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Printedobject;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $pricestart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $priceend;

    public function __construct()
    {

        $this->pricestart = new\DateTimeImmutable();


    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPricestart(): ?\DateTimeInterface
    {
        return $this->pricestart;
    }

    public function getPriceend(): ?\DateTimeInterface
    {
        return $this->priceend;
    }

    public function setPriceend( $priceend): self
    {
        $this->priceend = $priceend;

        return $this;
    }

}
