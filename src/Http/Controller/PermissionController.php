<?php


namespace App\Http\Controller;


use App\Core\Services\PermessionService;
use App\Domain\Membre\Entity\Permissions;
use App\Domain\Membre\Form\PermissionType;
use App\Domain\Membre\Repository\PermissionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/permission")
 * Class UsersController
 */
class PermissionController extends AbstractController
{

    /**
     * @var PermissionsRepository
     */
    private PermissionsRepository $permissionsRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var PermessionService
     */
    private PermessionService $permessionService;
    public function __construct(PermissionsRepository  $permissionsRepository,
                                EntityManagerInterface $entityManager,
                                PermessionService $permessionService

    )
    {
        $this->permissionsRepository = $permissionsRepository;
        $this->entityManager = $entityManager;
        $this->permessionService=$permessionService;
    }

    /**
     * @Route("/", name="permission", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json($this->permissionsRepository->dataTable(), 200);
        }
        return $this->render('admin/membre/permission/index.html.twig');
    }
    /**
     * @Route("/new", name="new_permission", methods={"GET","POST"})
     */
    public function new (Request  $request){
        $permission= new Permissions();
        $form   = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);
       // dd($form->getData(),$request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
          $this->entityManager->persist($permission);
          $this->entityManager->flush();
          return $this->redirectToRoute('admin_permission');
        }
        return $this->render('admin/membre/permission/new.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/loadroute", name="load_route", methods={"GET","POST"},options={"expose"=true})
     */
    public function refreshGuardroute(Request $request){
        if ($request->isXmlHttpRequest()){
            return $this->json($this->permessionService->findNewGuardName(),200);
        }
        return $this->json("not found",400);
    }
}