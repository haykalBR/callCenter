<?php


namespace App\Core\Twig;


use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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
    public function getFilters()
    {
        return[
            new TwigFilter('qrCode',[$this,'qrCode'],[ 'is_safe' => ['html']])
        ];
    }
    public function qrCode($content, $options = []){
        $qrcode="http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl={$this->googleAuthenticatorService->getQRContent($this->token->getToken()->getUser())}";
        return "<img id='imageQRcode'  src={$qrcode} alt='QR Code' name='imageQRcode'/>";
    }
}