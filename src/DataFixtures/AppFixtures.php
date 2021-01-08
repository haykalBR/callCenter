<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Domain\Membre\Entity\RoleInterface;
use App\Domain\Membre\Entity\Roles;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Entity\Profile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

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

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, GoogleAuthenticatorInterface $googleAuthenticator)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->googleAuthenticator = $googleAuthenticator;
    }

    public function load(ObjectManager $manager)
    {

        $role= new Roles();
        $role->setGuardName(RoleInterface::ROLE_SUPER_ADMIN);
        $role->setName('ROLE Super Admin');
        $manager->persist($role);
        for ($i = 10; $i < 150; ++$i) {
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

            if ($i==10){
                $user->addAccessRoles($role);
            }
            //  $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
            $manager->persist($user);
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
            $manager->persist($profile);
        }
        $manager->flush();
    }
}
