<?php


namespace App\Domain\Membre\Subscriber;


use App\Domain\Membre\Service\UserPermissionsService;
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

    public function __construct(RequestStack $requestStack,UserPermissionsService $userPermissionsService)
    {
        $this->requestStack = $requestStack;
        $this->userPermissionsService = $userPermissionsService;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'showPassword',
            FormEvents::PRE_SUBMIT=>'onPermissionForm',
            FormEvents::PRE_SET_DATA=>'test',
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
        $form=$event->getForm();
        $data=$event->getData();
        if (self::EDIT_ROUTE == $this->requestStack->getCurrentRequest()->get('_route')) {
            $userPermissions=$this->userPermissionsService->getGrantPermissionn($data);
            $getRevokePermission=$this->userPermissionsService->getRevokePermission($data);
            $form->add('grantPermission', ChoiceType::class, [
                'choices'      => $userPermissions,
                'choice_label' => function ($choice) {
                    return $choice['id'];
                },
                'required' => false,
                'multiple'=>true,
            ]);
            $x=array_filter(array_keys($this->router->getRouteCollection()->all()), function ($value) {
                return 1;
            });
           dd($x);

            $form->add('grantPermission', ChoiceType::class, [
                'choices'      => $span,
                'choice_label' => function ($choice) {

                    return $choice;
                },
                'required' => false,
                'multiple'=>true,
            ]);

        }
    }
}