<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use App\Core\Enum\DataTableEnum;
use Doctrine\ORM\NonUniqueResultException;

trait BaseRepositoryTrait
{
    public function dataTable(): array
    {
        $request      =$this->requestStack->getCurrentRequest();
        $draw         = (int) ($request->query->all()['draw']);
        $start        = $request->query->all()['start'];
        $length       = $request->query->all()['length'];
        $search       = $request->query->all()['search'];
        $orders       = $request->query->all()['order'];
        $columns      = $request->query->all()['columns'];
        $hiddenColumn = $request->query->all('hiddenColumn');

        if (isset($columns)) {
            $column = '';
            foreach ($columns as $colums) {
                //     if ($colums['data']!="t_options"){
                if ('true' === $colums['searchable'] and false === mb_strpos($column, $colums['name'])) {
                    $column .= $colums['name'].' AS '.$colums['data'].',';
                }
                //   }

            }
        } else {
            $column = 't';
        }
        if (isset($hiddenColumn)) {
            foreach ($hiddenColumn as $colums) {
                if (false === mb_strpos($column, $colums['name'])) {
                    $column .= $colums['name'].' AS '.$colums['data'].',';
                }
            }
        }
        /**
         * Select table with Colmuns.
         */
        $qb = $this->createQueryBuilder('t')->select(rtrim($column, ','));
        /**
         * get Total Items in database.
         */
        $total = $this->createQueryBuilder('t')->select('count(t.id)');
        /**
         *  get Flitred Item.
         */
        $FilteredTotal = clone $total;
        if (isset($request->query->all()['join'])) {
            $joins = $request->query->all()['join'];

            foreach ($joins as $join) {

                    $qb->leftJoin($join['join'], $join['alias'], Expr\Join::WITH, $join['condition']);
                    $FilteredTotal->leftJoin($join['join'], $join['alias'], Expr\Join::WITH, $join['condition']);


            }
        }
        // dd($joins,$qb->getQuery()->getDQL());

        /*
         *  Set Start item
         */
        if (isset($start)) {
            $qb->setFirstResult((int) $start);
        }
        /*
         *  Set Length count item perpage  item
         */
        if (isset($length)) {
            $qb->setMaxResults((int) $length);
        }
        /*
         *  Set Ordred By cloumn
         */
        /*   if (isset($orders)) {
               foreach ($orders as $order) {
                   $qb->addOrderBy($columns[$order['column']]['name'], $order['dir']);
               }
           }*/
        /**
         *  Get List of search.
         */
        $searchlist = [];
        if (isset($columns) and isset($search) and '' !== $search['value']) {

            foreach ($columns as $column) {
                if ('true' === $column['searchable']) {
                    $searchlist[] = $qb->expr()->like('CAST('.$column['name'].' as text)', '\'%'.trim($search['value']).'%\''
                    );
                }
            }

            foreach ($hiddenColumn as $column) {
                $searchlist[] = $qb->expr()->like('CAST('.$column['name'].' as text)', '\'%'.trim($search['value']).'%\'');
            }
        }

        /*
         *  Custom Search
         */
        if (isset($request->query->all()['customSearch'])) {
            $customSearch       = $request->query->all()['customSearch'];

            foreach ($customSearch as $search) {
                /*
                 *  Type Text
                 */
                if (DataTableEnum::TEXT === $search['type']) {
                    if ('' !== $search['value']) {
                        $searchlist[] = $qb->expr()->like('CAST('.$search['name'].' as text)', '\'%'.trim($search['value']).'%\'');
                    }
                }
                /*
                 *  Type Array
                 */
                if (DataTableEnum::ARRAY === $search['type']) {
                    if (\array_key_exists('value', $search)) {
                        $searchlist[] = $qb->expr()->in($search['name'], $search['value']);
                    }
                }
                //TODO 2TYPE DATE and DateInterval
            }
        }
        /*
         *  if list search not empty serach
         */
        if (count($searchlist) != 0) {
            $qb->andWhere(new Expr\Andx($searchlist));
            $FilteredTotal->andWhere(new Expr\Andx($searchlist));
        }
        try {
            /**
             *  get all count itmes.
             */
            $recordsTotal = $total->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            $recordsTotal = 0;
        }
        try {
            /**
             *  get filtred  count itmes.
             */
            $recordsFiltered = $FilteredTotal->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            $recordsFiltered = 0;
        }

        /*      $arrar=[];
              foreach (as $item){
                  $ch="";
                           $ch.= '<a data-toggle="tooltip" title="edit user " href=""><i class="fa fa-edit "></i></a> ';
                           $ch.= '<a data-toggle="tooltip" title="remove user "  class="delete_user"  href=""><i class="fa fa-trash"></i></a> ';
                           $ch.= '<a data-toggle="tooltip" title="regnreate password "  class="password_user" data-user=""><i class="fa fa-key"></i></a> ';
                           $ch.='<input type="checkbox"  class="state_user" data-user=""  data-toggle="switchbutton"  href="" checked data-size="xs">';
                  $item['t_options']=$ch;
                  $arrar[]=$item;
              }*/
        /*   dd($qb->getQuery()->getScalarResult()[0]
           ,$qb->getQuery()->getResult(Query::HYDRATE_SCALAR)[0]
           );*/
        return [
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $qb->getQuery()->getScalarResult() ,
        ];
    }
}