<?php


namespace App\Http\Controller\Users;

use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Form\SearchUsersType;
use App\Domain\Membre\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users", name="users", methods={"GET","POST"},options={"expose"=true})
 */
class UsersController extends  AbstractController
{

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $form   = $this->createForm(SearchUsersType::class, null);
        if ($request->isXmlHttpRequest()) {
            return $this->json($this->userRepository->datatable(), 200);
        }

        return $this->render('admin/membre/users/index.html.twig', ['form' => $form->createView()]);
    }
}