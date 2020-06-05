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
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')"},
 *         "post"={"security"="is_granted('ROLE_USER') ","security_message"="Sorry, log in first."},
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN') or object.user == user","security_message"="Sorry, this is not your order."},
 *         "put"={"security"="is_granted('ROLE_ADMIN') or object.user == user"},
 *     }
 * )
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 * @ORM\EntityListeners({"App\OrderUserListener"})

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
     * @Groups({"order:read", "order:write","user:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"order:read", "order:write", "user:read"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read", "order:write", "user:read"})
     */
    private $shippingAdress;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user:read"})
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"order:read", "order:write"})
     *
     */
    public $user;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="orders",cascade={"persist"})
     * @Groups({"order:read","order:write"})
     */
    private $details;





    public function __construct()
    {
        $this->details = new ArrayCollection();
        $this->date = new\DateTimeImmutable();
        $this->status= 'Received';
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
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

    public function getAllObjects(){
        $array = [];
        $details =$this->getDetails();
        foreach ($details as $detail){

            array_push($array,$detail->getObjects());
        }
        return $array;
    }



}
