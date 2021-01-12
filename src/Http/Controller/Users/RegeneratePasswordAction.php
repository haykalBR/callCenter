<?php


namespace App\Http\Controller\Users;


use App\Core\Exception\ApiProblem;
use App\Core\Exception\ApiProblemException;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Event\MailRegeneratePasswordEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegeneratePasswordAction
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Regenerate Password for user
     * @param User $data
     * @param Request $request
     * @return User
     */
    public function __invoke(User  $data,Request $request)
    {
        $result= json_decode($request->getContent(), true);
        if (!key_exists('password',$result,)){
             throw  new ApiProblemException(400,ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);
        }
        $data->setPassword(
            $this->passwordEncoder->encodePassword(
                $data,
                $result['password']
            ));
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new MailRegeneratePasswordEvent($data,$result['password']));
       return $data;
    }

}