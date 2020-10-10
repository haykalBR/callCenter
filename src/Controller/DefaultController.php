<?php

namespace App\Controller;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultController extends AbstractController
{


    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/2fa", name="2fa_login")
     */
    public function check2Fa(GoogleAuthenticatorInterface $googleAuthenticatorService,TokenStorageInterface $token){
        $qrcode1=$googleAuthenticatorService->getQRContent($token->getToken()->getUser());
        $qrcode="http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl={$qrcode1}";
        return $this->render('admin/membre/security/2fa.html.twig', [
            'qrcode' => $qrcode,
        ]);
    }
}
