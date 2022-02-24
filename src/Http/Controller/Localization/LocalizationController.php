<?php
namespace App\Http\Controller\Localization;


use App\Domain\Localization\Repository\CountryRepository;
use App\Domain\Membre\Form\SearchUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/localization", name="localization_index")
 */
class LocalizationController extends AbstractController
{
    /**
     * @var CountryRepository
     */
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function __invoke(Request $request)
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