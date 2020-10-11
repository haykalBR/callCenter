<?php


namespace App\Core\Twig;


use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
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


    public function __construct(GoogleAuthenticatorInterface $googleAuthenticatorService, TokenStorageInterface $token)
    {

        $this->googleAuthenticatorService = $googleAuthenticatorService;
        $this->token = $token;
    }
    public function getFunctions()
    {
        return[
            new TwigFunction('qrCode',[$this,'qrCode'],[ 'is_safe' => ['html']])
        ];
    }
    public function qrCode($code){
        $url="http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=";
        $url.=$this->googleAuthenticatorService->getQRContent($this->token->getToken()->getUser())."".$code;
        return "<img id='imageQRcode' src={$url} alt='QR Code' name='imageQRcode' />";
    }
}