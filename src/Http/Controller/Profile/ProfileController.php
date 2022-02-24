<?php
namespace App\Http\Controller\Profile;


use App\Domain\Membre\Form\GoogleAuthenticationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile")
 */
class ProfileController extends AbstractController
{
    public function __invoke()
    {
        $form = $this->createForm(GoogleAuthenticationFormType::class, null);
        return $this->render('admin/membre/profile/profile.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }
}