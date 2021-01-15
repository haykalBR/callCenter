<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controller;

use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Form\GoogleAuthenticationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

/**
 * @Route("/profile")
 * Class ProfileController
 */
class ProfileController extends AbstractController
{
    const CODE ='code';
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

    /**
     * ProfileController constructor.
     */
    public function __construct(
        GoogleAuthenticatorInterface $googleAuthenticatorService,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ) {
        $this->googleAuthenticatorService = $googleAuthenticatorService;
        $this->entityManager              = $entityManager;
        $this->session                    = $session;
    }





    /**
     * Active and Desactive google Auth.
     *
     * @Route("/googleAuthentication", name="otp",  methods={"POST"})
     */
    public function googleAuthentication(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        if ($request->isXmlHttpRequest()) {
            $credentials = json_decode($request->getContent(), true);
            $token       = $csrfTokenManager->isTokenValid(
                new CsrfToken('google_authentication_form', $credentials['_token'])
            );
            if (!$token) {
                throw new InvalidCsrfTokenException();
            }
            /**
             * @var User $user
             */
            $user = $this->getUser();
            if ($request->isXmlHttpRequest()) {
                if ($credentials['state']) {
                    $user->setGoogleAuthenticatorSecret($this->session->get(self::CODE));
                    $message = 'Your account has been updated to two-factor authentication';
                } else {
                    $user->setGoogleAuthenticatorSecret('');
                    $message = 'Your account has been updated to Simple  authentication';
                }
                $this->entityManager->flush();

                return $this->json($message, 200);
            }
        }
        throw $this->createAccessDeniedException('allow xml http request ');
    }
}
