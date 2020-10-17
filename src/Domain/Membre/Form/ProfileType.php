<?php

namespace App\Domain\Membre\Form;

use App\Core\Enum\GenreEnum;
use App\Core\Enum\RelationShipEnum;
use App\Domain\Membre\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('gender',ChoiceType::class,[
                'label'=>'Genere',
                'choices' => GenreEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                    return GenreEnum::getTypeName($choice);
                },
                'multiple' => false,
                'attr'=>[
                    'required'=>true,
                    'autocomplete' => 'off'
                ],
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker form-control'],
                'html5' => false,
            ])
            ->add('address')
            ->add('mobile')
            ->add('telephone')
            ->add('relationShipStatus',ChoiceType::class,[
                'label'=>'relationShipStatus',
                'choices' => RelationShipEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                    return RelationShipEnum::getTypeName($choice);
                },
                'multiple' => false,
                'attr'=>[
                    'required'=>true,
                    'autocomplete' => 'off'
                ],
            ])
            ->add('codePostal')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
