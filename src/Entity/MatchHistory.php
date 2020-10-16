<?php

namespace App\Entity;

use App\Repository\MatchHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchHistoryRepository::class)
 */
class MatchHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $Data = [];

    /**
     * @ORM\Column(type="bigint")
     */
    private $IdMatch;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Region;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?array
    {
        return $this->Data;
    }

    public function setData(array $Data): self
    {
        $this->Data = $Data;

        return $this;
    }

    public function getIdMatch(): ?string
    {
        return $this->IdMatch;
    }

    public function setIdMatch(string $IdMatch): self
    {
        $this->IdMatch = $IdMatch;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): self
    {
        $this->Region = $Region;

        return $this;
    }
}
