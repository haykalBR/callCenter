<?php
namespace App\Http\Controller\Profile;


use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Form\GoogleAuthenticationFormType;
use App\Domain\Membre\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/profile/edit/{username}", name="profile_edit", methods={"GET","POST"},options={"expose"=true})
 * @Security("user == cuurnetUser")
 */
class EditProfileController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, User $cuurnetUser): Response
    {
        // TODO Separee Image in table and date picker in Extension
        $profile= $cuurnetUser->getProfile() ?? new Profile();
        $form   = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setFile($form->getData()->getFile());
            $profile->setUser($cuurnetUser);
            if (!$cuurnetUser->getProfile()) {
                $this->entityManager->persist($profile);
            }
            $this->entityManager->flush();
            return  $this->redirectToRoute('admin_profile_edit', ['username'=>$cuurnetUser->getUsername()]);
        }
        return $this->render('admin/membre/profile/edit.html.twig', [
            'user' => $cuurnetUser->getProfile(),
            'form' => $form->createView(),
        ]);
    }
}