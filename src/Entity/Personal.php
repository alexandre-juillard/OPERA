<?php

namespace App\Entity;

use App\Repository\PersonalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalRepository::class)]
class Personal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $entry_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $exit_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?int $manager_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $department = null;

    #[ORM\OneToOne(mappedBy: 'personal', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    #[ORM\OneToMany(targetEntity: Goal::class, mappedBy: 'personal')]
    private Collection $goals;

    #[ORM\ManyToMany(targetEntity: TeamMember::class, mappedBy: 'personal')]
    private Collection $teamMembers;

    #[ORM\OneToMany(targetEntity: EmployeeSentiments::class, mappedBy: 'personal', orphanRemoval: true)]
    private Collection $employeeSentiments;

    #[ORM\OneToMany(targetEntity: Workload::class, mappedBy: 'personal')]
    private Collection $workloads;

    #[ORM\OneToMany(targetEntity: Interview::class, mappedBy: 'interviewer')]
    private Collection $interviewsAsInterviewer;

    #[ORM\OneToMany(targetEntity: Interview::class, mappedBy: 'interviewee')]
    private Collection $interviewsAsInterviewee;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
        $this->teamMembers = new ArrayCollection();
        $this->employeeSentiments = new ArrayCollection();
        $this->workloads = new ArrayCollection();
        $this->interviewsAsInterviewer = new ArrayCollection();
        $this->interviewsAsInterviewee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entry_date;
    }

    public function setEntryDate(?\DateTimeInterface $entry_date): static
    {
        $this->entry_date = $entry_date;

        return $this;
    }

    public function getExitDate(): ?\DateTimeInterface
    {
        return $this->exit_date;
    }

    public function setExitDate(?\DateTimeInterface $exit_date): static
    {
        $this->exit_date = $exit_date;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getManagerId(): ?int
    {
        return $this->manager_id;
    }

    public function setManagerId(?int $manager_id): static
    {
        $this->manager_id = $manager_id;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        // unset the owning side of the relation if necessary
        if ($profile === null && $this->profile !== null) {
            $this->profile->setPersonal(null);
        }

        // set the owning side of the relation if necessary
        if ($profile !== null && $profile->getPersonal() !== $this) {
            $profile->setPersonal($this);
        }

        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, Goal>
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): static
    {
        if (!$this->goals->contains($goal)) {
            $this->goals->add($goal);
            $goal->setPersonal($this);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): static
    {
        if ($this->goals->removeElement($goal)) {
            // set the owning side to null (unless already changed)
            if ($goal->getPersonal() === $this) {
                $goal->setPersonal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamMember>
     */
    public function getTeamMembers(): Collection
    {
        return $this->teamMembers;
    }

    public function addTeamMember(TeamMember $teamMember): static
    {
        if (!$this->teamMembers->contains($teamMember)) {
            $this->teamMembers->add($teamMember);
            $teamMember->addPersonal($this);
        }

        return $this;
    }

    public function removeTeamMember(TeamMember $teamMember): static
    {
        if ($this->teamMembers->removeElement($teamMember)) {
            $teamMember->removePersonal($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, EmployeeSentiments>
     */
    public function getEmployeeSentiments(): Collection
    {
        return $this->employeeSentiments;
    }

    public function addEmployeeSentiment(EmployeeSentiments $employeeSentiment): static
    {
        if (!$this->employeeSentiments->contains($employeeSentiment)) {
            $this->employeeSentiments->add($employeeSentiment);
            $employeeSentiment->setPersonal($this);
        }

        return $this;
    }

    public function removeEmployeeSentiment(EmployeeSentiments $employeeSentiment): static
    {
        if ($this->employeeSentiments->removeElement($employeeSentiment)) {
            // set the owning side to null (unless already changed)
            if ($employeeSentiment->getPersonal() === $this) {
                $employeeSentiment->setPersonal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Workload>
     */
    public function getWorkloads(): Collection
    {
        return $this->workloads;
    }

    public function addWorkload(Workload $workload): static
    {
        if (!$this->workloads->contains($workload)) {
            $this->workloads->add($workload);
            $workload->setPersonal($this);
        }

        return $this;
    }

    public function removeWorkload(Workload $workload): static
    {
        if ($this->workloads->removeElement($workload)) {
            // set the owning side to null (unless already changed)
            if ($workload->getPersonal() === $this) {
                $workload->setPersonal(null);
            }
        }

        return $this;
    }

   /**
     * @return Collection<int, Interview>
     * Obtient la collection des entretiens où la personne est l'interviewer
     */
    public function getInterviewsAsInterviewer(): Collection
    {
        return $this->interviewsAsInterviewer;
    }

    public function addInterviewAsInterviewer(Interview $interview): self
    {
        if (!$this->interviewsAsInterviewer->contains($interview)) {
            $this->interviewsAsInterviewer[] = $interview;
            $interview->setInterviewer($this);
        }

        return $this;
    }

    public function removeInterviewAsInterviewer(Interview $interview): self
    {
        if ($this->interviewsAsInterviewer->removeElement($interview)) {
            // Définit le côté propriétaire sur null (sauf si déjà modifié)
            if ($interview->getInterviewer() === $this) {
                $interview->setInterviewer(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Interview>
     */
    public function getInterviewsAsInterviewee(): Collection
    {
        return $this->interviewsAsInterviewee;
    }

    public function addInterviewAsInterviewee(Interview $interview): self
    {
        if (!$this->interviewsAsInterviewee->contains($interview)) {
            $this->interviewsAsInterviewee[] = $interview;
            $interview->setInterviewee($this);
        }

        return $this;
    }

    public function removeInterviewAsInterviewee(Interview $interview): self
    {
        if ($this->interviewsAsInterviewee->removeElement($interview) && $interview->getInterviewee() === $this) {
            $interview->setInterviewee(null);
        }

        return $this;
    }
}
