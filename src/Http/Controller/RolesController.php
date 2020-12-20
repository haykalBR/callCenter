<?php
namespace App\Http\Controller;
use App\Domain\Membre\Entity\Roles;
use App\Domain\Membre\Form\RolesType;
use App\Domain\Membre\Repository\RolesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/roles")
 * Class RolesController
 */
class RolesController extends AbstractController
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
     * add permission to roles
     * @return Response
     * @Route("/", name="roles", methods={"GET","POST"})
     */
    public function index(RolesRepository $repository):Response{
        dd($repository->getRolesWithoutAdmin());
        return $this->render('admin/membre/roles/index.html.twig');
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="new_role", methods={"GET","POST"})
     */
    public function new(Request $request) :Response{
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