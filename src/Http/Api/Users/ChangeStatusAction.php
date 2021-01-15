<?php


namespace App\Http\Api\Users;


use App\Core\Exception\ApiProblem;
use App\Core\Exception\ApiProblemException;
use App\Domain\Membre\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ChangeStatusAction
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(User $data,Request $request)
    {
        $result=json_decode($request->getContent(), true);
        if (!key_exists('status',$result,)){
            throw  new ApiProblemException(400,ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);
        }
        $data->setEnabled($result['status']);
        $this->entityManager->flush();
        return ["Compte Update"] ;

    }

}