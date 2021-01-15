<?php


namespace App\Http\Controller\Permissions;


use App\Core\Services\PermessionService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateNewGuardAction
{
    /**
     * @var PermessionService
     */
    private PermessionService $permessionService;

    public function __construct(PermessionService $permessionService)
        {
            $this->permessionService = $permessionService;
        }
    public function __invoke()
        {

            $permissions=$this->permessionService->findNewGuardName();
            if (empty($permissions)){
                return  new JsonResponse('no permission added');
            }
            $this->permessionService->savePermission($permissions);
            return  new JsonResponse('all new permission added');
        }
}