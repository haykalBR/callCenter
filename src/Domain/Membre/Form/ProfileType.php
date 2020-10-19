<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Form;

use App\Core\Enum\GenreEnum;
use App\Core\Enum\RelationShipEnum;
use App\Domain\Membre\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('firstName', TextType::class, [
                    'required' => false,
            ])
            ->add('lastName', TextType::class,[
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices'      => GenreEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                    return GenreEnum::getTypeName($choice);
                },
                'multiple' => false,
            ])
            ->add('birthday', DateType::class, [
                'widget'   => 'single_text',
                'attr'     => ['class' => 'js-datepicker form-control'],
                'html5'    => false,
                'format'   => 'dd-MM-yyyy',
                'required' => false,
            ])
            ->add('address', TextareaType::class,[
                'required' => false,
            ])
            ->add('mobile', TextType::class,[
                'required' => false,
            ])
            ->add('telephone',TextType::class,[
                'required' => false,
            ])
            ->add('relationShipStatus', ChoiceType::class, [
                'choices'      => RelationShipEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                    return RelationShipEnum::getTypeName($choice);
                },
                'multiple' => false,
                'required' => false,
            ])
            ->add('codePostal', TextType::class,[
                'required' => false,
            ])
            ->add('file', FileType::class,[
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
