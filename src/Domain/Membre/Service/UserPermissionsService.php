<?php


namespace App\Domain\Membre\Service;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Entity\UserPermission;
use App\Domain\Membre\Repository\PermissionsRepository;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserPermissionsService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var PermissionsRepository
     */
    private PermissionsRepository $permissionsRepository;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface  $manager,PermissionsRepository $permissionsRepository,UserRepository $userRepository)
        {
            $this->manager = $manager;
            $this->permissionsRepository = $permissionsRepository;
            $this->userRepository = $userRepository;
        }

     public function CreateUserPermissions(User $user , array $data){
        if (!empty($data['grantPermission'])){
            $this->createGrantPermissionn($user,$data['grantPermission']);
        }
        if (!empty($data['revokePermission'])){

            $this->createRevokePermission($user,$data['revokePermission']);

        }
        $this->manager->flush();
     //   $userPermission= new UserPermission();
     }
     private function createGrantPermissionn(User  $user,array $date){

        foreach ($date as $item){
            $permission=$this->permissionsRepository->findOneBy(['guardName'=>$item]);
            if (!$permission){
                $permission=$this->permissionsRepository->find($item);
            }
            $userPermission= new UserPermission();
            $userPermission->setPermission($permission);
            $userPermission->setUser($user);
            $userPermission->setStatus(UserPermission::GRANT);
            $this->manager->persist($userPermission);
        }
     }
     private function createRevokePermission(User  $user,array $data){
        foreach ($data as $item)
        {
            //TODO a Corrigie
            $permission=$this->permissionsRepository->findOneBy(['guardName'=>$item]);
            if (!$permission){
                $permission=$this->permissionsRepository->find($item);
            }
             $userPermission= new UserPermission();
             $userPermission->setPermission($permission);
             $userPermission->setUser($user);
             $userPermission->setStatus(UserPermission::REVOKE);
             $this->manager->persist($userPermission);
         }
     }
     public function removeUserPermissions(User $user){
        foreach ($user->getUserPermissions() as $userPermission){
            $this->manager->remove($userPermission);
        }
        $this->manager->flush();
     }
     public function getGrantPermissionn(User $user){
        return $this->userRepository->getGrantPermissionByUser($user);
     }
     public function getRevokePermission(User $user){
         return $this->userRepository->getRevokePermissionByUser($user);
     }


}