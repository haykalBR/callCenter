<?php


namespace App\Http\Api\Users;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetRolesFromUserAction
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function __invoke(User $data ,Request $request)
    {
        return $this->userRepository->getRolesByUser($data);
    }

}