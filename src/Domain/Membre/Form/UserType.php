<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Form;

use App\Domain\Membre\Entity\Roles;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\RolesRepository;
use App\Domain\Membre\Subscriber\UserFormSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{


    /**
     * @var UserFormSubscriber
     */
    private UserFormSubscriber $userFormSubscriber;

    public function __construct(UserFormSubscriber $userFormSubscriber)
    {
        $this->userFormSubscriber = $userFormSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('enabled', CheckboxType::class, [
                'attr' => [
                    'checked'   => 'checked',
                ],
            ])
            ->add('accessRoles', EntityType::class, [
                'class' => Roles::class,
                'choice_label' => 'name',
                'multiple'=>true,
                'by_reference' => false,
                'required' => true,
                'query_builder' => function (RolesRepository $repository) {
                    return $repository->getRolesWithoutAdmin();
                },
            ])
            ->add('profile', ProfileType::class)
            ->add('grantPermission', ChoiceType::class,[
                'mapped'=>false,
                'required' => false,
                'multiple'=>true,
            ])
            ->add('revokePermission', ChoiceType::class,[
                'mapped'=>false,
                'required' => false,
                'multiple'=>true,
            ])
        ;
        $builder->addEventSubscriber($this->userFormSubscriber);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
