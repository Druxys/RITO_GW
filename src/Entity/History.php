<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 */
class History
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
     * @ORM\Column(type="string", length=255)
     */
    private $SummonerName;

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

    public function getSummonerName(): ?string
    {
        return $this->SummonerName;
    }

    public function setSummonerName(string $SummonerName): self
    {
        $this->SummonerName = $SummonerName;

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
