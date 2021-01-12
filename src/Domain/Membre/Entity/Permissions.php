<?php

namespace App\Domain\Membre\Entity;
use App\Core\Traits\SoftDeleteTrait;
use App\Core\Traits\TimestampableTrait;
use App\Domain\Membre\Repository\PermissionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**

 * )
 * @ORM\Entity(repositoryClass=PermissionsRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
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
    private $name;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $guardName;

    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, mappedBy="roleHasPermissions")
     */
    private $roles;
    /**
     * @ORM\OneToMany(targetEntity=UserPermission::class, mappedBy="permission")
     */
    private Collection $userPermissions;
    /**
     * @ORM\ManyToMany(targetEntity=Resources::class, mappedBy="permissions")
     */
    private $resources;
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->userPermissions= new ArrayCollection();
        $this->resources=new ArrayCollection();
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

    public function __toString()
    {
        return $this->name;
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
            $userPermission->setPermission($this);
        }

        return $this;
    }

    public function removeUserPermission(UserPermission $userPermission): self
    {
        if ($this->userPermissions->contains($userPermission)) {
            $this->userPermissions->removeElement($userPermission);
            // set the owning side to null (unless already changed)
            if ($userPermission->getPermission() === $this) {
                $userPermission->setPermission(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Resources[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }
    public function addResource(Resources $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
        }

        return $this;
    }
    public function removeResource(Resources $resource): self
    {
        if ($this->roles->contains($resource)) {
            $this->roles->removeElement($resource);
        }
        return $this;
    }
}
