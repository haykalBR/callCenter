<?php

namespace App\Domain\Membre\Entity;
use App\Core\Traits\SoftDeleteTrait;
use App\Core\Traits\TimestampableTrait;
use App\Domain\Membre\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Roles implements RoleInterface
{
    use TimestampableTrait,SoftDeleteTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $guardName;

    /**
     * @ORM\ManyToMany(targetEntity=Permissions::class, inversedBy="roles")
     */
    private $roleHasPermissions;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="roles")
     */
    private $users;

    public function __construct()
    {
        $this->roleHasPermissions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuardName(): ?string
    {
        return $this->guardName;
    }

    public function setGuardName(string $guardName): self
    {
        $this->guardName = $guardName;

        return $this;
    }

    /**
     * @return Collection|Permissions[]
     */
    public function getRoleHasPermissions(): Collection
    {
        return $this->roleHasPermissions;
    }

    public function grantPermission(Permissions $roleHasPermission): self
    {
        if (!$this->roleHasPermissions->contains($roleHasPermission)) {
            $this->roleHasPermissions[] = $roleHasPermission;
        }

        return $this;
    }

    public function revokePermission(Permissions $roleHasPermission): self
    {
        if ($this->roleHasPermissions->contains($roleHasPermission)) {
            $this->roleHasPermissions->removeElement($roleHasPermission);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function hasPermission($permission)
    {
        return $this->getRoleHasPermissions()->exists(function($key, $value) use ($permission){
            return $value->getGuardName() === $permission;
        });
    }

    public function isSuperAdmin()
    {
        return $this->getGuardName() === static::ROLE_SUPER_ADMIN;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

}
