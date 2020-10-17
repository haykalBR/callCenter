<?php
namespace App\Http\Controller;
use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Form\GoogleAuthenticationFormType;
use App\Domain\Membre\Form\Profile1Type;
use App\Domain\Membre\Form\ProfileType;
use App\Domain\Membre\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/profile")
 * Class ProfileController
 * @package App\Http\Controller
 */
class ProfileController extends  AbstractController
{
    const CODE ="code";
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $googleAuthenticatorService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;
    public function __construct(GoogleAuthenticatorInterface $googleAuthenticatorService, EntityManagerInterface $entityManager,SessionInterface $session)
    {

        $this->googleAuthenticatorService = $googleAuthenticatorService;
        $this->entityManager = $entityManager;
        $this->session = $session;
    }
    /**
     * @Route("/edit/{username}", name="profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        //TODO TEST IF USER == uSER CURRENR fi annotaion route
        $form = $this->createForm(ProfileType::class, $user->getProfile());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_profile');
        }
        return $this->render('admin/membre/profile/edit.html.twig', [
            'user' => $user->getProfile(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("", name="profile")
     */
    public function profile(){
        $form = $this->createForm(GoogleAuthenticationFormType::class, null);
        return $this->render('admin/membre/profile/profile.html.twig', [
            'user'=>$this->getUser(),
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/googleAuthentication", name="otp",  methods={"POST"})
     */
    public function googleAuthentication(Request $request,CsrfTokenManagerInterface $csrfTokenManager)
    {

        if ($request->isXmlHttpRequest()) {
            $credentials = json_decode($request->getContent(), true);
            $token = $csrfTokenManager->isTokenValid(new CsrfToken('google_authentication_form', $credentials['_token']));
                if (!$token) {
                    throw new InvalidCsrfTokenException();
                }
             /**
              * @var $user User
              */
             $user = $this->getUser();
             if ($request->isXmlHttpRequest()) {
                 if ($credentials['state']) {
                     $user->setGoogleAuthenticatorSecret($this->session->get(self::CODE));
                     $message = "Your account has been updated to two-factor authentication";
                 } else {
                     $user->setGoogleAuthenticatorSecret("");
                     $message = "Your account has been updated to Simple  authentication";
                 }
                 $this->entityManager->flush();
                 return $this->json($message, 200);
             }
        }
        throw $this->createAccessDeniedException('allow xml http request ');


    }

}