<?php


namespace App\Domain\Membre\Form;


use App\Core\Enum\RelationShipEnum;
use App\Core\Services\PermessionService;
use App\Domain\Membre\Entity\Permissions;
use App\Domain\Membre\Repository\PermissionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionType extends AbstractType
{
    /**
     * @var PermessionService
     */
    private PermessionService $permessionService;
    /**
     * @var SelectTransformer
     */
    private SelectTransformer $selectTransformer;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(PermessionService $permessionService,RequestStack $requestStack)
    {
        $this->permessionService = $permessionService;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('guardName', ChoiceType::class, [
                'choices'      => $this->permessionService->findNewGuardName(),

                'choice_label' => function ($choice) {
                    return $choice;
                },
                'multiple' => false,

            ]);
         $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSet']);
    }

    public function onPreSet(FormEvent $event)
    {
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Permissions::class,

        ]);
    }
}