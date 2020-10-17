<?php

namespace App\Domain\Membre\Form;

use App\Domain\Membre\Entity\Profile;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Profile1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,[
                'label' => 'firstName d '
            ])
         //   ->add('lastName')
            ->add('gender')
            ->add('birthday',\DateTime::class,[
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker form-control'],
                'html5' => false,
                'format' => 'dd-MM-yyyy',
            ])
            ->add('address')
            ->add('mobile')
            ->add('telephone')
            ->add('relationShipStatus')
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
