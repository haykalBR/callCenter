<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(RouterInterface $router,Request  $request,UserRepository $repository,EntityManagerInterface  $entityManager): Response
    {
        // dd(88);
        $result = array_filter(array_keys($router->getRouteCollection()->all()), function ($v) {
            return preg_match('/admin_/', $v);
        });
        // dd($result);
        if ($request->isXmlHttpRequest()){


            $start=$request->query->all()['start'];
            $length=$request->query->all()['length'];
            $queryBuilder = $entityManager->getRepository(User::class)->createQueryBuilder('u')->select('u.id ,u.username,u.email,u.enabled');
            $total = $entityManager->getRepository(User::class)->createQueryBuilder('u')->select('count(u.id)');

            $FilteredTotal = clone $total;
            if (isset($start) and null != $start) {
                $queryBuilder->setFirstResult((int) $start);
            }

            if (isset($length) and null != $length) {
                $queryBuilder->setMaxResults((int) $length);
            }
            try {
                $recordsTotal = $total->getQuery()->getSingleScalarResult();
            } catch (NonUniqueResultException $e) {
                $recordsTotal = 0;
            }
            try {
                $recordsFiltered = $FilteredTotal->getQuery()->getSingleScalarResult();
            } catch (NonUniqueResultException $e) {
                $recordsFiltered = 0;
            }
            $output = [
               // 'request' => $request,
                'draw' => $request->query->all()['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $queryBuilder->getQuery()->getScalarResult(),
            ];
            return $this->json($output,200);
            }

        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
