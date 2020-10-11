<?php


namespace App\Core\Twig;


use App\Http\Controller\ProfileController;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class QrCodeExtension extends AbstractExtension
{
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $googleAuthenticatorService;
    /**
     * @var TokenStorageInterface
     */
    private $token;
    /**
     * @var SessionInterface
     */
    private $session;


    public function __construct(GoogleAuthenticatorInterface $googleAuthenticatorService, TokenStorageInterface $token,SessionInterface  $session)
    {
        $this->googleAuthenticatorService = $googleAuthenticatorService;
        $this->token = $token;
        $this->session = $session;
    }
    public function getFunctions()
    {
        return[
            new TwigFunction('qrCode',[$this,'qrCode'],[ 'is_safe' => ['html']])
        ];
    }
    public function qrCode(){
        $url="http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=";
        $url.=$this->googleAuthenticatorService->getQRContent($this->token->getToken()->getUser());
        $var = explode('secret=', $url);
        $this->session->set(ProfileController::CODE,$var[1]);
        return "<img id='imageQRcode' src={$url} alt='QR Code' name='imageQRcode' />";
    }
}