<?php

namespace App\Entity;

use App\Repository\WebsiteCheckRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteCheckRepository::class)]
class WebsiteCheck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Website::class, inversedBy: 'checks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Website $website = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?bool $isUp = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $checkedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isUp(): ?bool
    {
        return $this->isUp;
    }

    public function setIsUp(bool $isUp): self
    {
        $this->isUp = $isUp;
        return $this;
    }

    public function getCheckedAt(): ?\DateTimeInterface
    {
        return $this->checkedAt;
    }

    public function setCheckedAt(\DateTimeInterface $checkedAt): self
    {
        $this->checkedAt = $checkedAt;
        return $this;
    }
}
