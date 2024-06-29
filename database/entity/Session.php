<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Sessions')]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', nullable: false)]
    private $id;

    #[ORM\Column(name: 'UserID', type: 'integer', nullable: false)]
    private $userId;

    #[ORM\Column(name: 'PHPsession_id', type: 'string', length: 255, nullable: false)]
    private $phpSessionId;

    #[ORM\Column(name: 'UserIP', type: 'string', length: 255, nullable: false)]
    private $userIp;

    // Getters e Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getPhpSessionId(): ?string
    {
        return $this->phpSessionId;
    }

    public function setPhpSessionId(string $phpSessionId): self
    {
        $this->phpSessionId = $phpSessionId;
        return $this;
    }

    public function getUserIp(): ?string
    {
        return $this->userIp;
    }

    public function setUserIp(string $userIp): self
    {
        $this->userIp = $userIp;
        return $this;
    }
}