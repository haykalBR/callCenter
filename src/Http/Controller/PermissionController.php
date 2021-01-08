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
 * Class PermissionController
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
     * Get All item in DataBase Permission
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
     * Creation Permission
     * @Route("/new", name="new_permission", methods={"GET","POST"})
     */
    public function new (Request  $request):Response
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
    /**
     * Edit Permission
     * @Route("/edit/{id}", name="edit_permission", methods={"GET","POST"},options={"expose"=true})
     */
    public function edit (Request  $request,Permissions $permission):Response
    {
        $form   = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_permission');
        }
        return $this->render('admin/membre/permission/edit.html.twig', ['form' => $form->createView()]);
    }
    /**
     * Get New Guard Add in app
     * @Route("/loadroute", name="load_route", methods={"GET","POST"},options={"expose"=true})
     */
    public function refreshGuardroute(Request $request):Response
    {
        if ($request->isXmlHttpRequest()){
            return $this->json($this->permessionService->findNewGuardName(),200);
        }
        return $this->json("not found",400);
    }
    /**
     * Creation all new Guard in database
     * @Route("/addpermission", name="add_new_permission", methods={"POST"},options={"expose"=true})
     */
    public function addNewPermission(Request $request):Response
    {
        if ($request->isXmlHttpRequest()){
            $permissions=$this->permessionService->findNewGuardName();
            if (count($permissions)==0){
                return $this->json('no permission added',200);
            }
            $this->permessionService->savePermission($permissions);
            return $this->json('all new permission added',200);
        }
        return $this->json('Not Xml Http Request',400);
    }
}