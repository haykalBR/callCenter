<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Http\Controller\ProfileController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

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

    public function __construct(GoogleAuthenticatorInterface $googleAuthenticatorService, TokenStorageInterface $token, SessionInterface $session)
    {
        $this->googleAuthenticatorService = $googleAuthenticatorService;
        $this->token                      = $token;
        $this->session                    = $session;
    }

    public function getFunctions()
    {
        return[
            new TwigFunction('qrCode', [$this, 'qrCode'], ['is_safe' => ['html']]),
        ];
    }

    public function qrCode()
    {
        $url = 'http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=';
        if ('' === $this->token->getToken()->getUser()->getGoogleAuthenticatorSecret()) {
            $code = $this->googleAuthenticatorService->generateSecret();
            $url .= $this->googleAuthenticatorService->getQRContent($this->token->getToken()->getUser()).''.$code;
        } else {
            $url .= $this->googleAuthenticatorService->getQRContent($this->token->getToken()->getUser());
            $code = explode('secret=', $url)[1];
        }
        $this->session->set(ProfileController::CODE, $code);

        return "<img id='imageQRcode' src={$url} alt='QR Code' name='imageQRcode' />";
    }
}
