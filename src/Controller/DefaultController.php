<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index( RouterInterface $router, Request $request, UserRepository $repository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
  

        $result = array_filter(array_keys($router->getRouteCollection()->all()), function ($v) {
            return preg_match('/admin_/', $v);
        });
        if ($request->isXmlHttpRequest()) {
            $output =$userRepository->dataTable();

            return $this->json($output);
        }

        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
