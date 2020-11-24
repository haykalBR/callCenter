<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Data\Membre\Normalizer;

use App\Domain\Membre\Entity\User;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class UserNormalizer implements ContextAwareNormalizerInterface
{
    const EXPORT_USERS="export_users";
    const CONTEXT_USER="user_context";
    const CONTEXT_PROFILE="profile_context";
    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User && 'export_users' === $format;
    }

    /**
     * @param User $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {

        $users_list= [
        'id'                       => $object->getId(),
        'username'                 => $object->getUsername(),
        'email'                    => $object->getEmail(),
        'enabled'                  => $object->getEnabled(),
        'createdAt'                => $object->getCreatedAt(),
        'updatedAt'                => $object->getUpdatedAt(),
        'deletedAt'                => $object->getDeletedAt(),
        'googleAuthenticatorSecret'=> $object->getGoogleAuthenticatorSecret(),
      ];
        $profile_list=[];
        $profile     =$object->getProfile();
        if ($profile) {
            $profile_list=[
              'Id_user'   => $object->getUsername(),
              'id'        => $profile->getId(),
              'FirstName' => $profile->getFirstName(),
              'LastName'  => $profile->getLastName(),
              'Adress'    => $profile->getAddress(),
              'Birthday'  => $profile->getBirthday(),
              'CodePostal'=> $profile->getCodePostal(),
              'Telephone' => $profile->getTelephone(),
              'Gender'    => $profile->getGender(),
              'CreatedAt' => $profile->getCreatedAt(),
              'UpdatedAt' => $profile->getUpdatedAt(),
          ];
        }
        $response = [$users_list, $profile_list];
        if (!empty($context)){
            if ($context[0]==self::CONTEXT_USER){
                $users_list['error']=$context[1];
                $response=$users_list;
            }
            if ($context[0]==self::CONTEXT_PROFILE){
                $response=$profile_list;
            }
        }
        return  $response;
    }
}
