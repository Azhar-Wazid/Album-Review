<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $reviewScore = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $reviewDetails = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userID = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Album $albumID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReviewScore(): ?int
    {
        return $this->reviewScore;
    }

    public function setReviewScore(int $reviewScore): static
    {
        $this->reviewScore = $reviewScore;

        return $this;
    }

    public function getReviewDetails(): ?string
    {
        return $this->reviewDetails;
    }

    public function setReviewDetails(string $reviewDetails): static
    {
        $this->reviewDetails = $reviewDetails;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUserID(): ?User
    {
        return $this->userID;
    }

    public function setUserID(?User $userID): static
    {
        $this->userID = $userID;

        return $this;
    }

    public function getAlbumID(): ?Album
    {
        return $this->albumID;
    }

    public function setAlbumID(?Album $albumID): static
    {
        $this->albumID = $albumID;

        return $this;
    }
}
