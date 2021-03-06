<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
        collectionOperations={
 *         "get"={"security_message"="Only Boog can see all users."},
 *         "post",
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN') or object == user","security_message"="Sorry, this is not you."},
 *         "put"={"security"="is_granted('ROLE_ADMIN') or object == user"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 *
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write","order:read","order:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write","order:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write","order:write","order:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="user",cascade={"persist"})
     * @Groups({"user:read"})
     */
    private $orders;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $surname;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $register;

    /**
     * @Groups({"user:read", "user:write","order:read","order:write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Streetnumber;

    /**
     * @Groups({"user:read", "user:write","order:read","order:write"})
     * @ORM\ManyToOne(targetEntity=Postcode::class)
     */
    private $postcode;



    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection|Orders[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {

            $this->orders[] = $order;
            $order->setUser($this);



        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getRegister(): ?string
    {
        return $this->register;
    }

    public function setRegister(string $register): self
    {
        $this->register = $register;

        return $this;
    }

    public function getStreetnumber(): ?int
    {
        return $this->Streetnumber;
    }

    public function setStreetnumber(?int $Streetnumber): self
    {
        $this->Streetnumber = $Streetnumber;

        return $this;
    }

    public function getPostcode(): ?Postcode
    {
        return $this->postcode;
    }

    public function setPostcode(?Postcode $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }
}
