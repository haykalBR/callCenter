<?php


namespace App\Domain\Membre\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Core\Traits\SoftDeleteTrait;
use App\Core\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserPermissionRepository::class)
 * @ORM\Table(name="`user_permission`")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 */
class UserPermission
{
    const REVOKE=0;
    const GRANT=1;
    use TimestampableTrait,SoftDeleteTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userPermissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity=Permissions::class, inversedBy="userPermissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $permission;
    /**
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private bool  $status;
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param mixed $permission
     */
    public function setPermission($permission): void
    {
        $this->permission = $permission;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
    public function hasPermission($permission)
    {
       // dd($this->permission->getGuardName(),2);
       return $this->permission->getGuardName() === $permission;
    }

}