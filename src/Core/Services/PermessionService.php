<?php


namespace App\Core\Services;


use App\Domain\Membre\Entity\Permissions;
use App\Domain\Membre\Repository\PermissionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class PermessionService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;
    /**
     * @var PermissionsRepository
     */
    private PermissionsRepository $permissionsRepository;

    public function __construct(EntityManagerInterface $entityManager,RouterInterface $router,PermissionsRepository $permissionsRepository)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->permissionsRepository = $permissionsRepository;
    }
    /**
     * Laod and save all permissions in database
     */
    public function load():void{
        $this->savePermission($this->allGuardRoute());
    }
    /**
     * Creation permissions
     * @param $permissions
     */
    public function savePermission($permissions):void{
        foreach ($permissions as $item){
            $permession=new Permissions();
            $permession->setGuardName($item);
            $permession->setName( str_replace('_', ' ', $item));
            $this->entityManager->persist($permession);
        }
        $this->entityManager->flush();
    }
    /**
     * get all guard name
     * @return array
     */
    public function allGuardRoute():array{
        return array_filter(array_keys($this->router->getRouteCollection()->all()), function ($value) {
            return preg_match('/admin_/', $value);
        });
    }
    /**
     * get new guard name
     * @return array
     */
    public function findNewGuardName():array{
        $new_guard=[];
        foreach ($this->permissionsRepository->findGuardName() as $guard){
            $new_guard[]=$guard['guardName'];
        }
       return array_diff($this->allGuardRoute(),$new_guard);
    }
}