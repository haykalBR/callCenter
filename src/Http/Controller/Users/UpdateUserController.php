<?php


namespace App\Http\Controller\Users;

use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/users/edit/{id}", name="edit_users", methods={"GET","POST"},options={"expose"=true})
 */
class UpdateUserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function __invoke(Request $request,User $user)
     {
         $form   = $this->createForm(UserType::class, $user);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $this->entityManager->flush();

             return  $this->redirectToRoute('admin_users');
         }

         return $this->render('admin/membre/users/edit.html.twig', ['form' => $form->createView()]);
     }
}