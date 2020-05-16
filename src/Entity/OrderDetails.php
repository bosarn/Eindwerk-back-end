<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"order:read","detail:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"order:write", "detail:write"}, "swagger_definition_name"="Write"},
 * )
 * @ORM\Entity(repositoryClass=OrderDetailsRepository::class)
 *
 */
class OrderDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"detail:read","detail:write","order:write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"detail:read","order:read","detail:write","order:write"})
     */
    private $objectStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail:read","detail:write","order:write"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Printedobject::class, inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail:read","detail:write","order:read","order:write"})
     */
    private $objects;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getObjectStatus(): ?string
    {
        return $this->objectStatus;
    }

    public function setObjectStatus(string $objectStatus): self
    {
        $this->objectStatus = $objectStatus;

        return $this;
    }

    public function getOrders(): ?orders
    {
        return $this->orders;
    }

    public function setOrders(?orders $orders): self
    {
        $this->orders = $orders;

        return $this;
    }

    public function getObjects(): ?printedobject
    {
        return $this->objects;
    }

    public function setObjects(?printedobject $objects): self
    {
        $this->objects = $objects;

        return $this;
    }



}
