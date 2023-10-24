<?php

namespace App\Entity;

use App\Persistence\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{


    #[ORM\Column(type: "integer")]
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    private $id;

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $uid;
    #[ORM\Column(type: "string", length: 191)]
    private $name;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\NotBlank]
    private $username;

    #[ORM\Column(type: "string", length: 191, unique: true)]
    #[Assert\Email]
    #[Assert\NotBlank]
    private $email;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $avatar;

    #[ORM\Column(type: "json")]
    private $roles = [];

    #[ORM\Column(type: "string")]
    private $password;
    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;
    #[ORM\Column(type: "datetime_immutable", nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->uid = Uuid::v7();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid():?Uuid
    {
        return $this->uid;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return 'username';
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->password = null;
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

    public function getAvatar(): ?string
    {
        return !empty($this->avatar) ? $this->avatar : '/images/default.jpg';
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }
}
