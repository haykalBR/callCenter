<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Repository;

use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Entity\UserPermission;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Repository\BaseRepositoryTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    use BaseRepositoryTrait;

    private RequestStack $requestStack;

    public function __construct(ManagerRegistry $registry, RequestStack $requestStack)
    {
        parent::__construct($registry, User::class);
        $this->requestStack = $requestStack;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
    public function getGrantPermissionByUser(User $user){
        return $this->createQueryBuilder('u')
            ->select('up.id,p.guardName')
            ->innerJoin('u.userPermissions','up')
            ->innerJoin('up.permission','p')
            ->Where('u.id = up.user')
            ->andWhere('u.id = :id')
            ->andWhere('up.status = :status')
            ->setParameter('id', $user->getId())
            ->setParameter('status', UserPermission::GRANT)
            ->getQuery()->getResult();
    }

    public function getRevokePermissionByUser(UserInterface $user){
        return $this->createQueryBuilder('u')
            ->select('up.id,p.guardName')
            ->innerJoin('u.userPermissions','up')
            ->innerJoin('up.permission','p')
            ->Where('u.id = up.user')
            ->andWhere('u.id = :id')
            ->andWhere('up.status = :status')
            ->setParameter('id', $user->getId())
            ->setParameter('status', UserPermission::REVOKE)
            ->getQuery()->getResult();
    }

    /**
     * get roles from user
     * @param UserInterface $user
     * @return array|int|string
     */
    public function getRolesByUser(UserInterface $user){
        return $this->createQueryBuilder('u')
        ->Select('r.name')
            ->innerJoin("u.accessRoles", "r")
            ->where('u.id = :user')
            ->setParameter('user',$user->getId())
            ->getQuery()
            ->getScalarResult();
    }
}
