<?php


namespace App\Http\Controller\Roles;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roles", name="roles", methods={"GET","POST"})
 */
class RolesController extends AbstractController
{

    /**
     * @return Response
     */
    public function __invoke():Response
    {
        return $this->render('admin/membre/roles/index.html.twig');
    }

}