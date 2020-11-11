<?php


namespace App\Infrastructure\Maker;


use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FakeDataCommand extends AbstractMakeCommand
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(string $projectDir, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($projectDir);
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected static $defaultName = 'next:data';
    protected function configure(): void
    {
        $this
            ->setDescription('Crée des fake data with command line ')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->progressStart(8000);
        for ($i = 1000; $i < 9000; ++$i) {
            /**
             * @var $user User
             */
            $user = new User();
            $user->setEmail("haikelbrinis{$i}@gmail.com");
            $user->setUsername("haikel{$i}");
            $user->setEnabled(true);
            $user->setPassword(
                $this->userPasswordEncoder->encodePassword($user, 'haikel')
            );
            //  $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
            $this->entityManager->persist($user);
            /**
             * @var $profile Profile-
             */
            $profile = new Profile();
            $profile->setAddress("addres{$i}");
            $profile->setFirstName("haikel{$i}");
            $profile->setLastName("brinis{$i}");
            $profile->setGender(1);
            $profile->setBirthday(new \DateTime());
            $profile->setUser($user);
            $this->entityManager->persist($profile);
            $io->progressAdvance();

        }
        $this->entityManager->flush();
        $io->progressFinish();
        $io->success('Les contenus ont bien été enregistrés');
        return 0;
    }
}