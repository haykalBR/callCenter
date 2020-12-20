<?php


namespace App\Domain\Membre\Subscriber;


use App\Core\Services\PermessionService;
use App\Domain\Membre\Entity\Permissions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class PermissionsFormSubscriber implements EventSubscriberInterface
{
    CONST ROUTE_EDIT="admin_edit_permission";
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var PermessionService
     */
    private PermessionService $permessionService;

    public function __construct(RequestStack $requestStack,PermessionService $permessionService)
    {
        $this->requestStack = $requestStack;
        $this->permessionService = $permessionService;
    }
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSet',
            FormEvents::PRE_SUBMIT=> 'onPreSUBMIT'
        ];
    }
    public function onPreSet(FormEvent  $event){
        $form=$event->getForm();
        /**
         * @var $data Permissions
         */
        $data=$event->getData();
        $guradName = $this->permessionService->findNewGuardName();
        if ($this->requestStack->getCurrentRequest()->get("_route") ==self::ROUTE_EDIT){

            $form->add('guardName', ChoiceType::class, [
                'choices'      => [$data->getGuardName()],
                'choice_label' => function ($data) {
                    return $data;
                },
                'multiple' => false,
                'disabled' => true


            ]);
        }

    }
    public function onPreSUBMIT(FormEvent $event)
    {
        if ($this->requestStack->getCurrentRequest()->get("_route") != Self::ROUTE_EDIT){
            $form=$event->getForm();
            $item=$this->requestStack->getCurrentRequest()->request->all()['permission']['guardName'];
            $guradName = $this->permessionService->findNewGuardName();
            if (!in_array($item,$guradName)){
                array_push($guradName,$item);
            }
            $form->add('guardName', ChoiceType::class, [
                'choices'      => $guradName,
                'choice_label' => function ($choice) {
                    return $choice;
                },
                'multiple' => false,
            ]);
        }
    }

}