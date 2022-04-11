<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorRepository::class)
 */
class Doctor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="integer")
     */
    private $section_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $years;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\OneToMany(targetEntity=Paciente::class, mappedBy="doctor")
     */
    private $pacientes;

    public function __construct()
    {
        $this->pacientes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getSectionId(): ?int
    {
        return $this->section_id;
    }

    public function setSectionId(int $section_id): self
    {
        $this->section_id = $section_id;

        return $this;
    }

    public function getYears(): ?int
    {
        return $this->years;
    }

    public function setYears(int $years): self
    {
        $this->years = $years;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeInterface $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @return Collection|Paciente[]
     */
    public function getPacientes(): Collection
    {
        return $this->pacientes;
    }

    public function addPaciente(Paciente $paciente): self
    {
        if (!$this->pacientes->contains($paciente)) {
            $this->pacientes[] = $paciente;
            $paciente->setDoctor($this);
        }

        return $this;
    }

    public function removePaciente(Paciente $paciente): self
    {
        if ($this->pacientes->removeElement($paciente)) {
            // set the owning side to null (unless already changed)
            if ($paciente->getDoctor() === $this) {
                $paciente->setDoctor(null);
            }
        }

        return $this;
    }
}
