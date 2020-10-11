<?php

namespace App\DataFixtures;

use App\Domain\Membre\Entity\Profile;
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
           * @var $user User
           */
          $user = new User();
          $user->setEmail("haikelbrinis{$i}@gmail.com");
          $user->setUsername("haikel{$i}");
          $user->setPassword(
              $this->userPasswordEncoder->encodePassword($user, 'haikel')
          );
          $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
          $manager->persist($user);
          /**
           * @var $profile Profile-
           */
          $profile= new Profile();
          $profile->setAddress("addres{$i}");
          $profile->setFirstName("haikel{$i}");
          $profile->setLastName("brinis{$i}");
          $profile->setGender(1);
          $profile->setBirthday(new \DateTime());
          $profile->setUser($user);
          $manager->persist($profile);
      }
          $manager->flush();
    }
}
