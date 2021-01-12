<?php
namespace App\Http\Controller\Users;

use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Event\MailAddUserEvent;
use App\Domain\Membre\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users/new", name="new_users", methods={"GET","POST"})
 */
class CreateUserController extends AbstractController
{

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

    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {

        $user   =new User();
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

        return $this->render('admin/membre/users/new.html.twig', ['form' => $form->createView()]);
    }

}