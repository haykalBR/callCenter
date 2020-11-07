<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Entity;

use App\Core\Traits\SoftDeleteTrait;
use App\Core\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déjà")
 * @UniqueEntity(fields={"email"}, message="Cette addresse mail déjà existe")
 */
class User implements UserInterface, TwoFactorInterface
{
    use  TimestampableTrait;
    use SoftDeleteTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;
    /**
     * @Assert\Length(
     *     min=6,max=20,minMessage="Your Username should be at least {{ limit }} characters",
     *     maxMessage="This value is too long. It should have {{ limit }} characters or less."
     *      )
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $username;
    /**
     * @ORM\Column(type="boolean",options={"default":true})
     */
    private bool  $enabled;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;
    /**
     * @var string|null
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @var Profile|null
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity=LoginAttempt::class, mappedBy="user")
     */
    private Collection $loginAttempts;

    public function __construct()
    {
        $this->loginAttempts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->username;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    /**
     * Return true if the user should do TOTP authentication.
     */
    public function isTotpAuthenticationEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    /**
     * Return the user name.
     */
    public function getTotpAuthenticationUsername(): string
    {
        return $this->username;
    }

    /**
     * Return the configuration for TOTP authentication.
     */
    public function getTotpAuthenticationConfiguration(): void
    {
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        /* @var Profile $profile */
        $this->profile = $profile;

        // set (or unset) the owning side of the relation if necessary
        $newUsers = null === $profile ? null : $this;
        if ($profile->getUser() !== $newUsers) {
            $profile->setUser($newUsers);
        }

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return Collection|LoginAttempt[]
     */
    public function getLoginAttempts(): Collection
    {
        return $this->loginAttempts;
    }

    public function addLoginAttempt(LoginAttempt $loginAttempt): self
    {
        if (!$this->loginAttempts->contains($loginAttempt)) {
            $this->loginAttempts[] = $loginAttempt;
            $loginAttempt->setUser($this);
        }

        return $this;
    }

    public function removeLoginAttempt(LoginAttempt $loginAttempt): self
    {
        if ($this->loginAttempts->contains($loginAttempt)) {
            $this->loginAttempts->removeElement($loginAttempt);
            // set the owning side to null (unless already changed)
            if ($loginAttempt->getUser() === $this) {
                $loginAttempt->setUser(null);
            }
        }

        return $this;
    }
}