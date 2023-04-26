<?php

namespace App\Entity;

use App\Repository\CounterPointRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CounterPointRepository::class)]
class CounterPoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $counter_id = null;

    #[ORM\Column]
    private ?float $countPoint = null;

    #[ORM\Column(length: 255)]
    private ?string $datetime = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $periodicUsage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCounterId(): ?int
    {
        return $this->counter_id;
    }

    public function setCounterId(int $counter_id): self
    {
        $this->counter_id = $counter_id;

        return $this;
    }

    public function getCountPoint(): ?float
    {
        return $this->countPoint;
    }

    public function setCountPoint(float $countPoint): self
    {
        $this->countPoint = $countPoint;

        return $this;
    }

    public function getDatetime(): ?string
    {
        return $this->datetime;
    }

    public function setDatetime(string $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPeriodicUsage(): ?float
    {
        return $this->periodicUsage;
    }

    public function setPeriodicUsage(float $periodicUsage): self
    {
        $this->periodicUsage = $periodicUsage;

        return $this;
    }
}
