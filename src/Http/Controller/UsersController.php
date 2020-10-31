<?php


namespace App\Http\Controller;


use App\Domain\Membre\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 * Class UsersController
 */
class UsersController extends  AbstractController
{

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public  function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="users", methods={"GET","POST"})
     * @return Response
     */
    public function index(Request $request):Response{
         if ($request->isXmlHttpRequest()){
             return $this->json($this->userRepository->dataTable(),200);
         }
        return $this->render('admin/membre/users/index.html.twig');
    }
}