<?php


namespace App\Http\Controller\Permissions;

use App\Domain\Membre\Entity\Permissions;
use App\Domain\Membre\Form\PermissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/permission/new", name="new_permission", methods={"GET","POST"},options={"expose"=true})
 */
class CreatePermissionController extends AbstractController
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
     * Creation Permission
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request):Response
    {
        $permission= new Permissions();
        $form   = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($permission);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_permission');
        }
        return $this->render('admin/membre/permission/new.html.twig', ['form' => $form->createView()]);
    }

}