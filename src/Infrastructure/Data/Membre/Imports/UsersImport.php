<?php


namespace App\Infrastructure\Data\Membre\Imports;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\UserRepository;
use App\Infrastructure\Data\Concerns\ToModel;
use App\Infrastructure\Data\Exceptions\CastingException;
use App\Infrastructure\Data\Membre\Normalizer\UserNormalizer;
use App\Infrastructure\Data\Validators\RowValidator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersImport implements ToModel
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var NormalizerInterface
     */
    private NormalizerInterface $normalizer;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    private RowValidator $rowValidator;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                ValidatorInterface $validator,
                                NormalizerInterface $normalizer,
                                UserRepository $userRepository,
                                RowValidator $rowValidator
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->normalizer = $normalizer;
        $this->userRepository = $userRepository;
        $this->rowValidator = $rowValidator;
    }

    /**
     * @param array $row
     * @return User|array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public  function model(array $row)
    {
        //TDOD verify  object / null
        try {
            $user = new User();
            $user->setUsername($row["B"]);
            $user->setEmail($row["C"]);
            $user->setEnabled($row["D"]);
            $user->setCreatedAt(new \DateTime($row["E"]));
            $user->setUpdatedAt(new \DateTime($row["F"]));
            $user->setPassword($this->passwordEncoder->encodePassword($user,'haikel'));
            //$user->setDeletedAt(new \DateTime($row[6]));
            $user->setGoogleAuthenticatorSecret($row["H"]);
            $errors=$this->validator->validate($user);
            if (count($errors)>0){
              return $this->normalizer->normalize($this->userRepository->findOneBy(['username'=>trim($row["B"])]),
                                UserNormalizer::EXPORT_USERS,
                                        [UserNormalizer::CONTEXT_USER,$this->rowValidator->validate($errors)] );
            }
            return $user;
        }catch (CastingException $exception){
                echo $exception->getMessage();
        }
    }
}