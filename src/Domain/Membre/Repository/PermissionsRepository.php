<?php

namespace App\Domain\Membre\Repository;

use App\Core\Repository\BaseRepositoryTrait;
use App\Domain\Membre\Entity\Permissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Permissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permissions[]    findAll()
 * @method Permissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionsRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(ManagerRegistry $registry, RequestStack $requestStack)
    {
        parent::__construct($registry, Permissions::class);
        $this->requestStack = $requestStack;
    }
    /**
     * get all name of guard
     */
    public function findGuardName(){
        return $this->createQueryBuilder('p')
               ->select('p.guardName')
               ->getQuery()
               ->getArrayResult();
    }
    /**
     * get Permission from roles
     * @param array $roles
     * @return int|mixed|string
     */
    public function getPermissionFromRoles(array $roles){
        return $this->createQueryBuilder('p')
            ->select('p.guardName,p.id')
            ->innerJoin('p.roles','r')
            ->andWhere('r.id IN (:ids)')
            ->setParameter('ids', $roles)
            ->getQuery()->getResult();

    }
}
