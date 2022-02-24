<?php


namespace App\Domain\Membre\Form;


use App\Core\Enum\RelationShipEnum;
use App\Core\Services\PermessionService;
use App\Domain\Membre\Entity\Permissions;
use App\Domain\Membre\Repository\PermissionsRepository;
use App\Domain\Membre\Subscriber\PermissionsFormSubscriber;
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
     * @var PermissionsFormSubscriber
     */
    private PermissionsFormSubscriber $permissionsFormSubscriber;

    public function __construct(PermessionService $permessionService,PermissionsFormSubscriber $permissionsFormSubscriber)
    {
        $this->permessionService = $permessionService;
        $this->permissionsFormSubscriber = $permissionsFormSubscriber;
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
         $builder->addEventSubscriber($this->permissionsFormSubscriber);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Permissions::class,

        ]);
    }


}