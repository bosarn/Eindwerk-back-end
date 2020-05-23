<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"detail:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"detail:write"}, "swagger_definition_name"="Write"},
 *     collectionOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "post"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMI`N')"}
 *     }
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
    public $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"order:read","order:write", "detail:write","user:read"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read","order:write","detail:write"})
     */
    private $objectStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="details",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Printedobject::class, inversedBy="details",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail:write","detail:read","order:write","order:read"})
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
