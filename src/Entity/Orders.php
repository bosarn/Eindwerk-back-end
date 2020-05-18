<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"order:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"order:write"}, "swagger_definition_name"="Write"},
 *
 * )
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read","user:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read", "order:write","user:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"order:read", "order:write", "user:read", "user:write"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read", "order:write"})
     */
    private $shippingAdress;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"order:read", "order:write"})
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"order:read", "order:write"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="orders")
     * @Groups({"order:read","order:write"})
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getShippingAdress(): ?string
    {
        return $this->shippingAdress;
    }

    public function setShippingAdress(string $shippingAdress): self
    {
        $this->shippingAdress = $shippingAdress;

        return $this;
    }

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(?string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|OrderDetails[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(OrderDetails $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setOrders($this);
        }

        return $this;
    }

    public function removeDetail(OrderDetails $detail): self
    {
        if ($this->details->contains($detail)) {
            $this->details->removeElement($detail);
            // set the owning side to null (unless already changed)
            if ($detail->getOrders() === $this) {
                $detail->setOrders(null);
            }
        }

        return $this;
    }



}
