<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Users')]
class User
{
    private $userLevel;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', nullable: false)]
    private $id;

    #[ORM\Column(
        type: 'string',
        length: 255,
        nullable: true,
        unique: true,
    )]
    private $username;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $email;

    #[ORM\ManyToOne(targetEntity: "UserLevel")]
    #[ORM\Column(type: 'integer', nullable: true, options: [
        "default" => 1
    ])]
    #[ORM\JoinColumn(name: "userlevel_id", referencedColumnName: "id", nullable: false)]
    private $userlevel_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $password;

    #[ORM\Column(
        type: 'boolean',
        nullable: false,
        options: ['default' => true],
    )]
    private $active;

    #[ORM\Column(
        type: 'datetime',
        nullable: false,
        insertable: false,
        updatable: false,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private $registration_date;

    #[ORM\Column(
        type: 'datetime',
        nullable: false,
        options: ['default' => 'CURRENT_TIMESTAMP'],
        columnDefinition: "DATETIME on update CURRENT_TIMESTAMP",
    )]

    private $lastchange_date;

    // Getters e Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserlevelId(): int
    {
        return $this->userLevel ? $this->userLevel->getId() : null;
    }

    public function setUserlevelId(int $userlevel_id): self
    {
        $this->userLevel_id = $userlevel_id;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function getLastchangeDate(): ?\DateTimeInterface
    {
        return $this->lastchange_date;
    }
}