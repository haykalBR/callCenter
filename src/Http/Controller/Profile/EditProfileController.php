<?php
namespace App\Http\Controller\Profile;


use App\Domain\Membre\Entity\Profile;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Form\GoogleAuthenticationFormType;
use App\Domain\Membre\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    /**
     * @var Server
     */
    private Server $glide;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    public function __construct(EntityManagerInterface $entityManager,ParameterBagInterface $parameterBag)
    {

        $this->entityManager = $entityManager;

        $this->parameterBag = $parameterBag;
    }

    public function __invoke(Request $request, User $cuurnetUser ): Response
    {
        // TODO Separee Image in table and date picker in Extension
        $profile= $cuurnetUser->getProfile() ?? new Profile();
        $form   = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dir=$this->parameterBag->get('kernel.project_dir');
            /**
             * @var $data UploadedFile
             */
            //todO SERVICE HEIGT WIDH 
            $data=$form->getData()->getFile();
             $server= ServerFactory::create([
                 'response' => new SymfonyResponseFactory(),
                 'source'=>$data->getPath(),
                 'cache'=>"$dir/public/test/cache",
             ]);



            return $server->getImageResponse($data->getFilename(), ['w'=> 500, 'or'=>'90','mark'=>'https://www.ibexa.co/var/site/storage/images/_aliases/social_network_image/9/7/4/6/86479-1-eng-GB/php-symfony-not-java.png']);




            $profile->setFile();
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