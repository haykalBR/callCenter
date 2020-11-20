<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Form;

use App\Core\Enum\GenreEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', TextType::class)
            ->add('roles', ChoiceType::class, [
                'label'    => 'Select ROLE',
                'choices'  => [
                    'Maybe' => null,
                    'Yes'   => true,
                    'No'    => false,
                ],
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'label'        => 'Select Genre',
                'choices'      => GenreEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                    return GenreEnum::getTypeName($choice);
                },
                'multiple' => true,
                'expanded' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
