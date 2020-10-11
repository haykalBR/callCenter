<?php
namespace App\Http\Controller;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileController extends  AbstractController
{
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $googleAuthenticatorService;
    /**
     * @var TokenStorageInterface
     */
    private $token;


    public function __construct(GoogleAuthenticatorInterface $googleAuthenticatorService, TokenStorageInterface $token)
    {

        $this->googleAuthenticatorService = $googleAuthenticatorService;
        $this->token = $token;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/profile", name="profile")
     */
    public function profile(){
        return $this->render('admin/membre/profile/profile.html.twig');
    }

    /**
     * @Route("/otp", name="otp",  methods={"GET","POST"})
     */
    public function otp(Request $request){
        if($request->isXmlHttpRequest()) {
            $result = json_decode($request->getContent(), true);
            //TODO CHeck hwo to valide Qrcode Is Vrai or not
            }
            return $this->redirectToRoute("admin_profile");
    }



}