<?php


namespace App\Http\Api\Profile;


use App\Domain\Membre\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GoogleAuthenticationAction
{
    const CODE ='code';
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(SessionInterface $session,EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Profile $data,Request $request)
    {
        $user=$data->getUser();
        $credentials = json_decode($request->getContent(), true);
        if ($credentials['state']) {
            $user->setGoogleAuthenticatorSecret($this->session->get(self::CODE));
            $message = 'Your account has been updated to two-factor authentication';
        } else {
            $user->setGoogleAuthenticatorSecret('');
            $message = 'Your account has been updated to Simple  authentication';
        }
        $this->entityManager->flush();
        return new JsonResponse($message);
    }

}