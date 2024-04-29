<?php

namespace App\Entity;

use App\Entity\Personal;
use App\Entity\TeamMember;
use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $teamName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: TeamMember::class)]
    private Collection $teamMembers;

    #[ORM\ManyToMany(targetEntity: Personal::class, mappedBy: 'teams')]
    private Collection $members;

    #[ORM\ManyToOne(inversedBy: 'teams')]
    private ?Manager $manager = null;

    public function __construct()
    {
        $this->teamMembers = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable(); // Ensure createdAt is always set
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(?string $teamName): self
    {
        $this->teamName = $teamName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getTeamMembers(): Collection
    {
        return $this->teamMembers;
    }

    public function addTeamMember(TeamMember $teamMember): self
    {
        if (!$this->teamMembers->contains($teamMember)) {
            $this->teamMembers[] = $teamMember;
            $teamMember->setTeam($this);
        }
        return $this;
    }

    public function removeTeamMember(TeamMember $teamMember): self
    {
        if ($this->teamMembers->removeElement($teamMember)) {
            if ($teamMember->getTeam() === $this) {
                $teamMember->setTeam(null);
            }
        }
        return $this;
    }

    public function addMember(Personal $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->addTeam($this);
        }
        return $this;
    }

    public function removeMember(Personal $member): self
    {
        if ($this->members->removeElement($member)) {
            $member->removeTeam($this);
        }
        return $this;
    }

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }
}
