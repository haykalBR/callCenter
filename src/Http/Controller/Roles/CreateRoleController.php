<?php
namespace App\Http\Controller\Roles;

use App\Domain\Membre\Entity\Roles;
use App\Domain\Membre\Form\RolesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roles/new", name="new_role", methods={"GET","POST"})
 * Class RolesController
 */
class CreateRoleController extends AbstractController
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
     * Create new Role
     * @param Request $request
     * @return Response
     */
        public function __invoke(Request $request) :Response
        {
            $role= new Roles();
            $form   = $this->createForm(RolesType::class, $role);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($role);
                $this->entityManager->flush();
                return $this->redirectToRoute('admin_roles');
            }
            return $this->render('admin/membre/roles/new.html.twig',['form'=>$form->createView()]);
        }
}