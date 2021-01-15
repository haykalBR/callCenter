<?php


namespace App\Http\Controller\Permissions;


use App\Core\Services\PermessionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class GetNewGuardAction
{
    /**
     * @var PermessionService
     */
    private PermessionService $permessionService;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(PermessionService $permessionService,Security $security)
    {
        $this->permessionService = $permessionService;
        $this->security = $security;
    }

    /**
     * Get New Guard Add in app
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request)
    {
        return $this->permessionService->findNewGuardName();
    }

}