<?php


namespace App\Domain\Membre\DataTransformer;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Service\UserPermissionsService;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Form;

    class PermissionTransformer implements DataTransformerInterface
{


    /**
     * @var UserPermissionsService
     */
    private UserPermissionsService $userPermissionsService;

    public function __construct(UserPermissionsService  $userPermissionsService)
    {

        $this->userPermissionsService = $userPermissionsService;
    }

    public function transform($user)
    {

        //CREATE RELATION
    }

    public function reverseTransform($user)
    {
    }
}