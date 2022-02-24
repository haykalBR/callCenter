<?php
namespace App\Http\Controller\Permissions;

use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Repository\PermissionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/permission", name="permission", methods={"GET","POST"},options={"expose"=true})
 */
class PermissionsController extends AbstractController
{
    /**
     * @var PermissionsRepository
     */
    private PermissionsRepository $permissionsRepository;

    public function __construct(PermissionsRepository $permissionsRepository)
    {
        $this->permissionsRepository = $permissionsRepository;
    }

    /**
     * Get All item in DataBase Permission
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request) :Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json($this->permissionsRepository->dataTable(), 200);
        }
        return $this->render('admin/membre/permission/index.html.twig');
    }
}