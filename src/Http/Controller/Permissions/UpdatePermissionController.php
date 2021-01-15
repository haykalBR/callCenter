<?php


namespace App\Http\Controller\Permissions;


use App\Domain\Membre\Entity\Permissions;
use App\Domain\Membre\Form\PermissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/permission/edit/{id}", name="edit_permission", methods={"GET","POST"},options={"expose"=true})
 */
class UpdatePermissionController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Edit Permission
     * @param Permissions $permission
     * @param Request $request
     * @return Response
     */
    public function __invoke(Permissions $permission,Request $request) :Response
    {
        $form   = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_permission');
        }
        return $this->render('admin/membre/permission/edit.html.twig', ['form' => $form->createView()]);
    }
}