<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostcodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PostcodeRepository::class)
 */
class Postcode
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
    private $gemeente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postcode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGemeente(): ?string
    {
        return $this->gemeente;
    }

    public function setGemeente(string $gemeente): self
    {
        $this->gemeente = $gemeente;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }
}
