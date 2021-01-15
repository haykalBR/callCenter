<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Twig;

use Twig\TwigFunction;
use App\Domain\Membre\Entity\User;
use Twig\Extension\AbstractExtension;
use App\Http\Controller\ProfileController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

class QrCodeExtension extends AbstractExtension
{
    private GoogleAuthenticatorInterface $googleAuthenticatorService;
    private TokenStorageInterface $token;
    private SessionInterface $session;

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

    /**
     * generate qr code.
     */
    public function qrCode(): string
    {
        /**** FAUT DANS ENV  */
        $url = 'http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=';
        /** @var User $user */
        $user=$this->token->getToken()->getUser();
        if ('' === $user->getGoogleAuthenticatorSecret()) {
            $code = $this->googleAuthenticatorService->generateSecret();
            $url .= $this->googleAuthenticatorService->getQRContent($user).''.$code;
        } else {
            $url .= $this->googleAuthenticatorService->getQRContent($user);
            $code = explode('secret=', $url)[1];
        }
        $this->session->set(ProfileController::CODE, $code);

        return "<img id='imageQRcode' src={$url} alt='QR Code' name='imageQRcode' />";
    }
}
