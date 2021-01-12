<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Core\Traits\SoftDeleteTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Core\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Http\Controller\Users\RegeneratePasswordAction;
use App\Http\Controller\Users\ChangeStatusAction;
use App\Http\Controller\Users\GetPermissionFromRolesAction;
use App\Http\Controller\Users\GetRolesFromUserAction;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *   "permission-from-roles"={
 *       "method"="get",
 *       "path"="/users/permission-from-roles",
 *       "openapi_context"={"summary"=" Get  permission  from  roles "},
 *       "controller"=GetPermissionFromRolesAction::class
 *      }
 *     },
 *     itemOperations={
 *     "delete"={
 *      "path"="users/delete/{id}",
 *       "method"="DELETE",
 *      },
 *     "regenerate-password"={
 *       "method"="PUT",
 *       "path"="/users/regenerate-password/{id}",
 *       "openapi_context"={"summary"="regenerate password for user "},
 *       "controller"=RegeneratePasswordAction::class,
 *       "normalization_context"={"groups"={"write:password"}}
 *      },
 *      "change-status"={
 *       "method"="PUT",
 *       "path"="/users/change-status/{id}",
 *       "openapi_context"={"summary"=" Change Status  for user "},
 *       "controller"=ChangeStatusAction::class
 *      },
 *      "roles-from-user"={
 *       "method"="get",
 *       "path"="/users/roles-from-user/{id}",
 *       "openapi_context"={"summary"=" Get  roles for user "},
 *       "controller"=GetRolesFromUserAction::class
 *      }
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déjà")
 * @UniqueEntity(fields={"email"}, message="Cette addresse mail déjà existe")
 */
class User extends UserInterface implements TwoFactorInterface
{
    use TimestampableTrait;
    use SoftDeleteTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"write:password"})
     */
    private int $id;
    /**
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"write:password"})
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
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups ({"blog_subresource"})
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
    /**
     * @ORM\OneToMany(targetEntity=UserPermission::class, mappedBy="user")
     */
    private Collection $userPermissions;
    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, inversedBy="users",cascade={"persist"})
     * @Assert\Count(min="1")
     */
    private $accessRoles;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    private $grantPermission;
    private $revokePermission;

    public function __construct()
    {
        $this->loginAttempts  = new ArrayCollection();
        $this->accessRoles    = new ArrayCollection();
        $this->userPermissions= new ArrayCollection();
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

    /**
     * @return Collection|Roles[]
     */
    public function getAccessRoles(): Collection
    {
        return $this->accessRoles;
    }

    public function addAccessRoles(Roles $accessRoles): self
    {
        if (!$this->accessRoles->contains($accessRoles)) {
            $this->accessRoles[] = $accessRoles;
            $accessRoles->addUser($this);
        }

        return $this;
    }

    public function removeAccessRoles(Roles $accessRoles): self
    {
        if ($this->accessRoles->contains($accessRoles)) {
            $this->accessRoles->removeElement($accessRoles);
            $accessRoles->removeUser($this);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roleNames = [];
        $roles     = $this->getAccessRoles();

        foreach ($roles as $role) {
            $roleNames[] = $role->getGuardName();
        }

        return array_unique($roleNames);
    }

    public function setAccessRoles(Collection $roles)
    {
        $this->accessRoles=$roles;
    }

    public function setRoles(array $roles): self
    {
        foreach ($roles as $role) {
            $this->addAccessRoles($role);
        }
    }

    public function hasPermission($permissionName): bool
    {
        $hasPermission = false;
        $collection    = collect($this->getUserPermissions()->toArray());
        foreach ($this->getAccessRoles() as $role) {
            if ($role->hasPermission($permissionName)) {
                $hasPermission= true;
            }
        }

        /*
         * if user has custom permission
         */
        if ($collection->count() > 0) {
            $grantPermission = $collection->filter(function ($item) {
                return UserPermission::GRANT == $item->getStatus();
            });
            /*
             * if user has grant permission
             */
            if ($grantPermission->count() > 0) {
                $grantPermissionArrayCollection=new ArrayCollection($grantPermission->toArray());
                $isGrantPermission             = $grantPermissionArrayCollection->map(function ($value) use ($permissionName,$hasPermission) {
                    if ($value->hasPermission($permissionName)) {
                        return $value;
                    }
                });

                if (null !== $isGrantPermission->current()) {
                    $hasPermission=true;
                }
            }
            $revokePermission = $collection->filter(function ($item) {
                return UserPermission::REVOKE == $item->getStatus();
            });

            /*
             * if user has revoke permission
             */
            if ($revokePermission->count() > 0) {
                $grantPermissionArrayCollection=new ArrayCollection($revokePermission->toArray());
                $isRevokePermission            = $grantPermissionArrayCollection->map(function ($value) use ($permissionName,$hasPermission) {
                    if ($value->hasPermission($permissionName)) {
                        return $value;
                    }
                });
                if (null !== $isRevokePermission->current()) {
                    $hasPermission=false;
                }
            }
        }

        return $hasPermission;
    }

    public function isSuperAdmin()
    {
        $isSuperAdmin = false;
        foreach ($this->getAccessRoles() as $role) {
            if ($role->isSuperAdmin()) {
                return true;
            }
        }

        return $isSuperAdmin;
    }

    public function hasRole(RoleInterface $role)
    {
        return $this->getAccessRoles()->contains($role);
    }

    public function getGrantPermission()
    {
        return $this->grantPermission;
    }

    public function setGrantPermission($grantPermission): void
    {
        $this->grantPermission = $grantPermission;
    }

    public function getRevokePermission()
    {
        return $this->revokePermission;
    }

    public function setRevokePermission($revokePermission): void
    {
        $this->revokePermission = $revokePermission;
    }

    /**
     * @return Collection|UserPermission[]
     */
    public function getUserPermissions(): Collection
    {
        return $this->userPermissions;
    }

    public function addUserPermission(UserPermission $userPermission): self
    {
        if (!$this->userPermissions->contains($userPermission)) {
            $this->userPermissions[] = $userPermission;
            $userPermission->setUser($this);
        }

        return $this;
    }

    public function removeUserPermission(UserPermission $userPermission): self
    {
        if ($this->userPermissions->contains($userPermission)) {
            $this->userPermissions->removeElement($userPermission);
            // set the owning side to null (unless already changed)
            if ($userPermission->getUser() === $this) {
                $userPermission->setUser(null);
            }
        }

        return $this;
    }
}
