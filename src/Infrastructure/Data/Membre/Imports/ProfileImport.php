<?php


namespace App\Infrastructure\Data\Membre\Imports;


use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Repository\ProfileRepository;
use App\Domain\Membre\Repository\UserRepository;
use App\Infrastructure\Data\Concerns\ToModel;
use App\Infrastructure\Data\Exceptions\CastingException;
use App\Infrastructure\Data\Membre\Normalizer\UserNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileImport implements ToModel
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var NormalizerInterface
     */
    private NormalizerInterface $normalizer;
    /**
     * @var ProfileRepository
     */
    private ProfileRepository $profileRepository;

    public function __construct(UserRepository $userRepository,ValidatorInterface $validator,NormalizerInterface $normalizer,ProfileRepository $profileRepository)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->normalizer = $normalizer;
        $this->profileRepository = $profileRepository;
    }

    /**
     * @inheritDoc
     */
    public  function model(array $row)
    {
        try {
            $user=$this->userRepository->findOneBy(['username'=>trim($row["A"])]);
            if (!$user)
                throw new \Exception('Not entity ');
            $profile= $user->getProfile()??new Profile();
            $profile->setFirstName(trim($row["C"]));
            $profile->setLastName(trim($row["D"]));
            $profile->setAddress(trim($row["E"]));
            $profile->setBirthday(new \DateTime($row["F"]));
            $profile->setCodePostal($row["G"]);
            $profile->setTelephone(trim($row["H"]));
            $profile->setGender(trim($row["I"]));
            $profile->setCreatedAt(new \DateTime($row["J"]));
            $profile->setUpdatedAt(new \DateTime($row["K"]));
            $profile->setUser($user);
            $errors=$this->validator->validate($profile);
            if (count($errors)>0){
                return $this->normalizer->normalize($this->profileRepository->find($row['B']),
                    UserNormalizer::EXPORT_USERS,[UserNormalizer::CONTEXT_PROFILE] );
            }
            return $profile;
        }catch (CastingException $exception){
            echo $exception->getMessage();
        }
    }
}