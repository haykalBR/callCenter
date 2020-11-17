<?php


namespace App\Infrastructure\Data\Membre\Normalizer;


use App\Domain\Membre\Entity\User;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class UserNormalizer implements ContextAwareNormalizerInterface
{

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User  && $format === "export_users" ;
    }

    /**
     * @param User $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|null
     */
    public function normalize($object, string $format = null, array $context = [])
    {

      $users= [
        "id"=>$object->getId(),
        "username"=>$object->getUsername(),
        "email"=>$object->getEmail(),
        "enabled"=>$object->getEnabled(),
        "createdAt"=>$object->getCreatedAt(),
        "updatedAt"=>$object->getUpdatedAt(),
        "deletedAt"=>$object->getDeletedAt(),
        "googleAuthenticatorSecret"=>$object->getGoogleAuthenticatorSecret(),
      ];
      $profile=[];
      if ($object->getProfile()){
          $profile=[
              'Id_user'=>$object->getId(),
              'FirstName'=>$object->getProfile()->getFirstName(),
              'LastName'=>$object->getProfile()->getLastName(),
              'Adress'=>$object->getProfile()->getAddress(),
              'Birthday'=>$object->getProfile()->getBirthday(),
              'CodePostal'=>$object->getProfile()->getCodePostal(),
              'Telephone'=>$object->getProfile()->getTelephone(),
              'Gender'=>$object->getProfile()->getGender(),
              'CreatedAt'=>$object->getProfile()->getCreatedAt(),
              'UpdatedAt'=>$object->getProfile()->getUpdatedAt(),
          ];
      }
      return  [$users,$profile];
    }
}