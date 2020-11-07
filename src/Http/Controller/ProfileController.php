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
     * Edit Profile User.
     *
     * @Route("/edit/{username}", name="profile_edit", methods={"GET","POST"})
     * @Security("user == cuurnetUser")
     */
    public function edit(Request $request, User $cuurnetUser): Response
    {
        // TODO Separee Image in table and date picker in Extension
        $profile= $cuurnetUser->getProfile() ?? new Profile();
        $form   = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setFile($form->getData()->getFile());
            $profile->setUser($cuurnetUser);
            if (!$cuurnetUser->getProfile()) {
                $this->entityManager->persist($profile);
            }
            $this->entityManager->flush();

            return  $this->redirectToRoute('admin_profile_edit', ['username'=>$cuurnetUser->getUsername()]);
        }

        return $this->render('admin/membre/profile/edit.html.twig', [
            'user' => $cuurnetUser->getProfile(),
            'form' => $form->createView(),
        ]);
    }

    /**
     *  Retunn Page Profile.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("", name="profile")
     */
    public function profile()
    {
        $form = $this->createForm(GoogleAuthenticationFormType::class, null);

        return $this->render('admin/membre/profile/profile.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
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