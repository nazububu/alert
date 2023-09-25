<?php

namespace App\Entity;

use App\Repository\SubscriberRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
#[ORM\Table(name: 'subscribers')]
class Subscriber
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $identifier;

    #[ORM\Column]
    private array $regions = [];

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Subscriber
    {
        $this->name = $name;

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): Subscriber
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getRegions(): array
    {
        return $this->regions;
    }

    public function setRegions(array $regions): Subscriber
    {
        $this->regions = $regions;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): Subscriber
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
