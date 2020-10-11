<?php
namespace App\Http\Controller;
use App\Domain\Membre\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/profile", name="profile")
     */
    public function profile(){
        return $this->render('admin/membre/profile/profile.html.twig',
            ['user'=>$this->getUser()]);
    }
    /**
     * @Route("/otp", name="otp",  methods={"GET","POST"})
     */
    public function otp(Request $request){
            $state = json_decode($request->getContent(), true)['state'];
            /**
             * @var $user User
             */
            $user= $this->getUser();
            if ($request->isXmlHttpRequest()){
                if($state){
                    $user->setGoogleAuthenticatorSecret($this->session->get(self::CODE));
                    $message="Your account has been updated to two-factor authentication";
                }else{
                    $user->setGoogleAuthenticatorSecret("");
                    $message="Your account has been updated to Simple  authentication";
                }
                $this->entityManager->flush();
                return $this->json($message,200);
            }

    }



}