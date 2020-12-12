<?php

namespace App\Domain\Membre\Entity;
use App\Domain\Membre\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolesRepository::class)
 */
class Roles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $guardName;

    /**
     * @ORM\ManyToMany(targetEntity=Permissions::class, inversedBy="roles")
     */
    private $roleHasPermissions;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="roles")
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

    public function addRoleHasPermission(Permissions $roleHasPermission): self
    {
        if (!$this->roleHasPermissions->contains($roleHasPermission)) {
            $this->roleHasPermissions[] = $roleHasPermission;
        }

        return $this;
    }

    public function removeRoleHasPermission(Permissions $roleHasPermission): self
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
}
