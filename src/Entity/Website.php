<?php

namespace App\Entity;

use App\Repository\WebsiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\WebsiteCheck;

#[ORM\Entity(repositoryClass: WebsiteRepository::class)]
class Website
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'websites')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?int $lastStatus = null;

    #[ORM\Column]
    private ?bool $isUp = false;

    #[ORM\OneToMany(mappedBy: 'website', targetEntity: WebsiteCheck::class, orphanRemoval: true)]
    private Collection $checks;

    public function __construct()
    {
        $this->checks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChecks(): Collection
    {
        return $this->checks;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getLastStatus(): ?int
    {
        return $this->lastStatus;
    }

    public function setLastStatus(?int $lastStatus): static
    {
        $this->lastStatus = $lastStatus;
        return $this;
    }

    public function isUp(): ?bool
    {
        return $this->isUp;
    }

    public function setIsUp(bool $isUp): static
    {
        $this->isUp = $isUp;
        return $this;
    }
}
