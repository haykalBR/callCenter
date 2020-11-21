<?php


namespace App\Infrastructure\Data\Membre\Imports;


use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Repository\UserRepository;
use App\Infrastructure\Data\Concerns\ToModel;
use App\Infrastructure\Data\Exceptions\CastingException;

class ProfileImport implements ToModel
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public  function model(array $row)
    {
        try {
            $profile=new Profile();
            $profile->setFirstName($row[1]);
            $profile->setLastName($row[2]);
            $profile->setAddress($row[3]);
            $profile->setBirthday(new \DateTime($row[4]));
            $profile->setCodePostal($row[5]);
            $profile->setTelephone($row[6]);
            $profile->setGender($row[7]);
            $profile->setCreatedAt(new \DateTime($row[8]));
            $profile->setUpdatedAt(new \DateTime($row[9]));
            $profile->setUser($this->userRepository->find($row[0]));
            return $profile;
        }catch (CastingException $exception){
            echo $exception->getMessage();
        }
    }
}