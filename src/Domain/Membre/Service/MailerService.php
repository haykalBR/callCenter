<?php


namespace App\Domain\Membre\Service;


use App\Domain\Membre\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class MailerService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;
    /**
     * @var Environment
     */
    private Environment $environment;

    public function __construct(MailerInterface $mailer,RouterInterface $router, Environment $environment)
        {
            $this->mailer = $mailer;
            $this->router = $router;
            $this->environment = $environment;
        }

    /**
     *  Send Email with detailes for user after added
     * @param User $user
     * @param string $password
     * @return Response
     */
     public function sendAddUser(User $user, string $password ) :void
     {
         try {
             $url = $this->router->generate('admin_app_login',[], UrlGeneratorInterface::ABSOLUTE_URL);
             $email = (new TemplatedEmail())
                 ->from('haikelbrinis@gmail.com')
                 ->to($user->getEmail())
                 ->subject('Time for Symfony Mailer!');
             $email ->htmlTemplate('admin/mailer/users/adduser.html.twig')
                 ->context([
                     'user' => $user,
                     'url'=> $url,
                     'password'=>$password
                 ]);
             $this->mailer->send($email);
         }catch (\Exception $exception){
             dd($exception->getMessage());
         }
     }
     public function changePasswordUser(User $user , string $password){
         try {
             $url = $this->router->generate('admin_app_login',[], UrlGeneratorInterface::ABSOLUTE_URL);
             $email = (new TemplatedEmail())
                 ->from('haikelbrinis@gmail.com')
                 ->to($user->getEmail())
                 ->subject('this is new Password!');
             $email ->htmlTemplate('admin/mailer/users/newpassword.html.twig')
                 ->context([
                     'user' => $user,
                     'url'=> $url,
                     'password'=>$password
                 ]);
             $this->mailer->send($email);
         }catch (\Exception $exception){

         }
     }
}