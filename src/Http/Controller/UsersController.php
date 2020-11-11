<?php


namespace App\Http\Controller;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Event\MailAddUserEvent;
use App\Domain\Membre\Event\MailRegeneratePasswordEvent;
use App\Domain\Membre\Form\ProfileType;
use App\Domain\Membre\Form\SearchUsersType;
use App\Domain\Membre\Form\UserType;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public  function __construct(UserRepository $userRepository,EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder,
                                    EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
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
            $this->eventDispatcher->dispatch(new MailAddUserEvent($user, $form->get('plainPassword')->getData()));
            return  $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/membre/users/new.html.twig',[ 'form' => $form->createView()]);
    }
    /**
     * @Route("/edit/{id}", name="edit_users", methods={"GET","POST"},options={"expose"=true})
     * @return Response
     */
    public function edit (Request $request, User $user) : Response{

        $form   = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return  $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/membre/users/edit.html.twig',[ 'form' => $form->createView()]);

     }
    /**
     * @Route("/remove/{id}", name="remove_users", methods={"GET","DELETE"},options={"expose"=true})
     * @return Response
     */
    public function delete(User $user, Request $request) : Response{
        //TODO DELETE EXCEPTION
       if ($request->isXmlHttpRequest()){
           try {
               $this->entityManager->remove($user);
               $this->entityManager->flush();
               return $this->json("User {$user->getEmail()} bien removed",200);
           } catch (\Exception $exception){
               return $this->json( $exception->getMessage(),400);
           }
       }
        return  $this->redirectToRoute('admin_users');
    }
    /**
     * @Route("/generate/password/{id}", name="generate_password_users", methods={"GET","POST"},options={"expose"=true})
     * @return Response
     */
    public function generatePassword(User $user ,Request $request): Response{
        if ($request->isXmlHttpRequest()){
            try {
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        json_decode($request->getContent(), true)['password']
                    ));
                $this->entityManager->flush();
                $this->eventDispatcher->dispatch(new MailRegeneratePasswordEvent($user,  json_decode($request->getContent(), true)['password']));
                return $this->json( 'succres password update ! ',200);
            }catch(\Exception $exception){
                return $this->json( $exception->getMessage(),400);
            }
        }
        return  $this->redirectToRoute('admin_users');
    }
    /**
     * @Route("/state//{id}", name="state_users", methods={"GET","POST"},options={"expose"=true})
     * @return Response
     */
    public function changeState(User $user,Request $request) :Response{
       if ($request->isXmlHttpRequest()){
           try {
               $user->setEnabled(json_decode($request->getContent(), true)['state']);
               $this->entityManager->flush();
               return $this->json( " Compte Update with State {json_decode($request->getContent(), true)['state']?'disabled':'enabled'} ",200);
           }catch (\Exception $exception){
               return $this->json( $exception->getMessage(),400);
           }
       }
       return  $this->redirectToRoute('admin_users');
    }
}