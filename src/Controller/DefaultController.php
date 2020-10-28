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
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Query\Expr;
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(RouterInterface $router,Request  $request,UserRepository $repository,EntityManagerInterface  $entityManager): Response
    {
        $result = array_filter(array_keys($router->getRouteCollection()->all()), function ($v) {
            return preg_match('/admin_/', $v);
        });
        if ($request->isXmlHttpRequest()){



            $draw = intval($request->query->all()['draw']);
            $start = $request->query->all()['start'];
            $length = $request->query->all()['length'];
            $search = $request->query->all()['search'];
            $orders = $request->query->all()['order'];
            $columns = $request->query->all()['columns'];
            if (isset($columns)) {
                $column = '';
                foreach ($columns as $colums) {
                    if ('true' == $colums['searchable'] and false === strpos($column, $colums['name'])) {
                        $column .= $colums['name'].' AS '.$colums['data'].',';
                    }
                }
            } else {
                $column = 't';
            }

            $qb = $entityManager->getRepository(User::class)->createQueryBuilder('t')->select(rtrim($column, ','));
            $total = $entityManager->getRepository(User::class)->createQueryBuilder('t')->select('count(t.id)');
            $FilteredTotal = clone $total;
            if (isset($start) and null != $start) {
                $qb->setFirstResult((int) $start);
            }
            if (isset($length) and null != $length) {
                $qb->setMaxResults((int) $length);
            }
            $searchlist = [];
            if (isset($columns) and isset($search) and '' != $search['value']) {
                foreach ($columns as $column) {
                    if ('true' == $column['searchable']) {
                        $searchlist[] = $qb->expr()->like($column['name'], '\'%'.$search['value'].'%\'');
                    }
                }
            }






            if (0 != count($searchlist)) {
                $qb->andWhere(new Expr\Orx($searchlist));
              //  $FilteredTotal->andWhere(new Expr\Orx($searchlist));
            }
          //  dump($w=$qb->getQuery());die;
            try {
                $w=$qb->getQuery()->getScalarResult();
            }catch (\Exception $exception){
                    var_dump($exception->getMessage());die;
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

            $output = array(
                'request' => $request,
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $qb->getQuery()->getScalarResult(),
            );
            return $this->json($output);

            }

        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
