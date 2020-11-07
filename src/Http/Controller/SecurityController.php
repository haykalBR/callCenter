<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controller;

use App\Core\Services\CaptchaValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var CaptchaValidator
     */
    private $captchaValidator;

    public function __construct(CaptchaValidator $captchaValidator)
    {
        $this->captchaValidator = $captchaValidator;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_profile');
        }
        $error        = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/membre/security/login.html.twig', ['last_username'   => $lastUsername, 'error' => $error,
                                                                            'captchakey' => $this->captchaValidator->getKey(), ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/2fa", name="2fa_login")
     */
    public function check2Fa(): Response
    {
        return $this->render('admin/membre/security/2fa.html.twig');
    }
}
