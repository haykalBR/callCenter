<?php

namespace App\Domain\Membre\Entity;
use App\Core\Traits\SoftDeleteTrait;
use App\Core\Traits\TimestampableTrait;
use App\Domain\Membre\Repository\PermissionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PermissionsRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Permissions
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
    private $guardName;

    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, mappedBy="roleHasPermissions")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * @return Collection|Roles[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addRoleHasPermission($this);
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeRoleHasPermission($this);
        }

        return $this;
    }
}
