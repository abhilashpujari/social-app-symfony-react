<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_GUEST = 'ROLE_GUEST';
    const ROLE_USER = 'ROLE_USER';

    /**
     * The fields that aren't mass assignable.
     *
     * @var array
     */
    const GUARDED_FIELDS = ['creationDate', 'isActive', 'lastAccessTime', 'roles'];

    /**
     * The fields that should be hidden.
     *
     * @var array
     */
    const HIDDEN_FIELDS = ['password'];

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=255,  unique=true)
     *
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     *
     */
    private $firstName;

    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $lastAccessTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $socialProvider;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $socialId;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate->format('c');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $this->hashPassword($password);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * @param $password
     * @return bool|string
     */
    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function getLastAccessTime(): ?string
    {
        return $this->lastAccessTime
            ? $this->lastAccessTime->format('c')
            : null;
    }

    public function setLastAccessTime(?\DateTimeInterface $lastAccessTime): self
    {
        $this->lastAccessTime = $lastAccessTime;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = self::ROLE_USER;
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSocialProvider(): ?string
    {
        return $this->socialProvider;
    }

    public function setSocialProvider(string $socialProvider): self
    {
        $this->socialProvider = $socialProvider;

        return $this;
    }

    public function getSocialId(): ?string
    {
        return $this->socialId;
    }

    public function setSocialId(string $socialId): self
    {
        $this->socialId = $socialId;

        return $this;
    }
}
