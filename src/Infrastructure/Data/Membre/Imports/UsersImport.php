<?php


namespace App\Infrastructure\Data\Membre\Imports;


use App\Domain\Membre\Entity\User;
use App\Infrastructure\Data\Concerns\ToModel;
use App\Infrastructure\Data\Exceptions\CastingException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersImport implements ToModel
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public  function model(array $row)
    {
        try {
            $user = new User();
            $user->setUsername($row[1]);
            $user->setEmail($row[2]);
            $user->setEnabled($row[3]);
            $user->setCreatedAt(new \DateTime($row[4]));
            $user->setUpdatedAt(new \DateTime($row[5]));
            $user->setPassword($this->passwordEncoder->encodePassword($user,'haikel'));
            //$user->setDeletedAt(new \DateTime($row[6]));
            $user->setGoogleAuthenticatorSecret($row[7]);
            return $user;
        }catch (CastingException $exception){
                echo $exception->getMessage();
        }
    }
}