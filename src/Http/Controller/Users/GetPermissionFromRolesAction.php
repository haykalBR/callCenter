<?php


namespace App\Http\Controller\Users;


use App\Core\Exception\ApiProblem;
use App\Core\Exception\ApiProblemException;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\PermissionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPermissionFromRolesAction
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var PermissionsRepository
     */
    private PermissionsRepository $permissionsRepository;

    public function __construct(EntityManagerInterface $entityManager,PermissionsRepository $permissionsRepository)
    {
        $this->entityManager = $entityManager;
        $this->permissionsRepository = $permissionsRepository;
    }
    public function __invoke(Request $request)
    {
        $result=json_decode($request->getContent(), true);
        if (!key_exists('roles',$result,)){
            throw  new ApiProblemException(400,ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);
        }
        $permissionsGrant=$this->permissionsRepository->getPermissionNotFromRoles($result['roles']);
        $permissionsRevoke=$this->permissionsRepository->getPermissionFromRoles($result['roles']);
        return  ['grant'=>$permissionsGrant,'revoke'=>$permissionsRevoke];
    }
}