<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controller;

use App\Domain\Localization\Repository\CountryRepository;
use App\Domain\Membre\Form\SearchUsersType;
use App\Domain\Membre\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/localization")
 */
class LocalizationController extends AbstractController
{

    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }
    /**
     * @Route("/", name="localization_index")
     */
    public function index(Request $request)
    {
        $form   = $this->createForm(SearchUsersType::class, Null);
        if ($request->isXmlHttpRequest()){
            return $this->json($this->countryRepository->dataTable(),200);
        }
        return $this->render('admin/localization/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
