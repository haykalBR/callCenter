<?php

namespace App\Http\Controller;

use App\Core\Exception\RecaptchaException;
use App\Core\Services\CaptchaValidator;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Form\ChangePasswordFormType;
use App\Domain\Membre\Form\ResetPasswordRequestFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    /**
     * @var CaptchaValidator
     */
    private $captchaValidator;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper,CaptchaValidator $captchaValidator)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->captchaValidator = $captchaValidator;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("", name="forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }
        return $this->render('admin/membre/reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
             'captchakey'=>$this->captchaValidator->getKey()
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reset/{token}", name="reset_password")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        if ($token) {
            $this->storeTokenInSession($token);
            return $this->redirectToRoute('admin_reset_password');
        }
        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('admin_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->isValid())
                $this->addFlash('reset_password_error','haikel');
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();
            $this->cleanSessionAfterReset();
            return $this->redirectToRoute('admin_login');
        }

        return $this->render('admin/membre/reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);


        // Marks that you are allowed to see the app_check_email page.
        $this->setCanCheckEmailInSession();

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('admin_forgot_password_request');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
             $this->addFlash('reset_password_error', sprintf(
                 'There was a problem handling your password reset request - %s',
                $e->getReason()
            ));
            return $this->redirectToRoute('admin_forgot_password_request');
        }
        $email = (new TemplatedEmail())
            ->from(new Address('haikelbrinis1@gmail.com', 'Football'))
          //  ->to($user->getEmail())
             ->to('haikelbrinis@gmail.com')
            ->subject('Your password reset request')
            ->htmlTemplate('admin/membre/reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
            ])
        ;
        $mailer->send($email);
        $heure=  intval(gmdate("H", $this->resetPasswordHelper->getTokenLifetime()));
        $this->addFlash('success', "An email has been sent that contains a link that you can click to reset
                                                your password. This link will expire in {$heure} hour(s).");
        return $this->redirectToRoute('admin_forgot_password_request');
    }
}
