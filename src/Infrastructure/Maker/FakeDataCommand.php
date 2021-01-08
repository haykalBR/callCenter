<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Maker;

use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FakeDataCommand extends AbstractMakeCommand
{
    protected static $defaultName = 'next:data';

    private EntityManagerInterface $entityManager;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(string $projectDir, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($projectDir);
        $this->entityManager       = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

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
