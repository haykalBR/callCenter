<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/localization")
 */
class LocalizationController extends AbstractController
{
    /**
     * @Route("/", name="localization_index")
     */
    public function index()
    {
        return $this->render('admin/localization/index.html.twig', [
            'controller_name' => 'LocalizationController',
        ]);
    }
}
