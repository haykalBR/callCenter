<?php


namespace App\Domain\Membre\Subscriber;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\PermissionsRepository;
use App\Domain\Membre\Service\UserPermissionsService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use function Amp\Promise\all;

class UserFormSubscriber implements EventSubscriberInterface
{
    const EDIT_ROUTE= 'admin_edit_users';

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var UserPermissionsService
     */
    private UserPermissionsService $userPermissionsService;
    /**
     * @var PermissionsRepository
     */
    private PermissionsRepository $permissionsRepository;

    public function __construct(RequestStack $requestStack,UserPermissionsService $userPermissionsService,PermissionsRepository $permissionsRepository)
    {
        $this->requestStack = $requestStack;
        $this->userPermissionsService = $userPermissionsService;
        $this->permissionsRepository = $permissionsRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'showPassword',
            FormEvents::PRE_SUBMIT=>'onPermissionForm',
         //   FormEvents::PRE_SET_DATA=>'test',
            ];
    }
    public function showPassword(FormEvent $event)
    {

        $form=$event->getForm();
        if (self::EDIT_ROUTE !== $this->requestStack->getCurrentRequest()->get('_route')) {
            $form->add('plainPassword', RepeatedType::class, [
                'type'        => PasswordType::class,
                'mapped'      => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min'        => 10,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max'        => 4096,
                    ]),
                 /*   new Regex([
                        'pattern' => '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@$!%*#?&]).{7,}/',
                        'message' => ' password not in  pattern one letter, one lettre maj , one number and one special character ',
                    ]),*/
                ],
                'first_options'   => ['label' => 'Password'],
                'second_options'  => ['label' => 'Confirm Password'],
                'invalid_message' => 'Your password does not match the confirmation.',
            ]);
        }
        $this->test($event);
    }
    public function onPermissionForm(FormEvent $event){

        $form=$event->getForm();
        $grantPermission=[];
        $data = $event->getData();
        if (array_key_exists('grantPermission',$data)){
           $grantPermission=$this->requestStack->getCurrentRequest()->request->all()['user']['grantPermission'];
       }
        $revokePermission=[];
        if (array_key_exists('revokePermission',$data)){
            $revokePermission=$this->requestStack->getCurrentRequest()->request->all()['user']['revokePermission'];
        }
        $this->grantPermission($form,$grantPermission);
        $this->revokePermission($form,$revokePermission);
    }
    private function grantPermission($form ,$grantPermission ){
        $form->add('grantPermission', ChoiceType::class, [
            'choices'      => $grantPermission,
            'choice_label' => function ($choice) {
                return $choice;
            },
            'required' => false,
            'multiple'=>true,
        ]);
    }
    private function revokePermission($form ,$revokePermission ){
        $form->add('revokePermission', ChoiceType::class, [
            'choices'      => $revokePermission,
            'choice_label' => function ($choice) {
                return $choice;
            },
            'required' => false,
            'multiple'=>true,
        ]);
    }
    public function test(FormEvent $event){

        if (self::EDIT_ROUTE == $this->requestStack->getCurrentRequest()->get('_route') ) {
            $form=$event->getForm();
            /**
             * @var $data  User
             */
            $data=$event->getData();
            $rolesIds = $data->getAccessRoles()->map(function($value)  {
                return $value->getid();
            });
            $userGrantPermissions=$this->mappedArray($this->permissionsRepository->getPermissionNotFromRoles($rolesIds->toArray()),'guardName');
            $userRevokePermissions=$this->mappedArray($this->permissionsRepository->getPermissionFromRoles($rolesIds->toArray()),'guardName');
            $form->add('grantPermission', ChoiceType::class, [
                'choices'      => $userGrantPermissions,
                'choice_label' => function ($userGrantPermissions) {
                    return $userGrantPermissions;
                },
                'required' => false,
                'multiple'=>true,
                'data' => $this->mappedArray($this->userPermissionsService->getGrantPermissionn($data),'guardName')
            ]);

            $form->add('revokePermission', ChoiceType::class, [
                'choices'      => $userRevokePermissions,
                'choice_label' => function ($userRevokePermissions) {
                    return $userRevokePermissions;
                },
                'required' => false,
                'multiple'=>true,
                'data' => $this->mappedArray($this->userPermissionsService->getRevokePermission($data),'guardName')
            ]);

        }
    }
    private function mappedArray(array $data,string $key):array{
        $collection=new ArrayCollection($data);
        $mappedCollection = $collection->map(function($value) use ($key) {
            return $value[$key];
        });
        return $mappedCollection->toArray();
    }
}