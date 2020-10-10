<?php

namespace App\DataFixtures;

use App\Domain\Membre\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $googleAuthenticator;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder ,GoogleAuthenticatorInterface $googleAuthenticator)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->googleAuthenticator = $googleAuthenticator;
    }
    public function load(ObjectManager $manager)
    {
      for ($i=0;$i<10;$i++){
          /**
           * @var User
           */
          $user = new User();
          $user->setEmail("haikelbrinis{$i}@gmail.com");
          $user->setUsername("haikel{$i}");
          $user->setPassword(
              $this->userPasswordEncoder->encodePassword($user, 'haikel')
          );
          $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
          $manager->persist($user);
      }
          $manager->flush();
    }
}
