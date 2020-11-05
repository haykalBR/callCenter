<?php


namespace App\Http\Controller;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Form\ProfileType;
use App\Domain\Membre\Form\SearchUsersType;
use App\Domain\Membre\Form\UserType;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users",options={"expose"=true})
 * Class UsersController
 */
class UsersController extends  AbstractController
{

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public  function __construct(UserRepository $userRepository,EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="users", methods={"GET","POST"})
     * @return Response
     */
    public function index(Request $request):Response{
        $form   = $this->createForm(SearchUsersType::class, Null);
        if ($request->isXmlHttpRequest()){
             return $this->json($this->userRepository->dataTable(),200);
         }
        return $this->render('admin/membre/users/index.html.twig',[ 'form' => $form->createView()]);
    }

    /**
     * @Route("/new", name="new_users", methods={"GET","POST"})
     * @return Response
     */
    public function new(Request  $request) : Response{
        $user=new User();
        $form   = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return  $this->redirectToRoute('admin_new_users');
        }
        return $this->render('admin/membre/users/new.html.twig',[ 'form' => $form->createView()]);


    }

}