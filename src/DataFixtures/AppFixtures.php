<?php

namespace App\DataFixtures;

use App\Domain\Membre\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    public function load(ObjectManager $manager)
    {
      for ($i=0;$i<10;$i++){
          /**
           * @var User
           */
          $user = new User();
          $user->setEmail("haikelbrinis{$i}@gmail.com");
          $user->setPassword(
              $this->userPasswordEncoder->encodePassword($user, 'haikel')
          );
          $manager->persist($user);
      }
          $manager->flush();
    }
}
