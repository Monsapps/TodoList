<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control my-2',
                    'placeholder' => 'Votre nom d\'utilisateur'
                ]
            ]);

        $passMapped = false;
        $passRequired = false;

        // In mode create we have a password mapped to the user
        if($options['create']) {
            $passMapped = true;
            $passRequired = true;
        }

        $builder   
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => $passRequired,
                'mapped' => $passMapped,
                'first_options'  => ['label' => 'Mot de passe',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control my-2',
                    'placeholder' => 'Mot de passe'
                ]],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control my-2',
                    'placeholder' => 'Vérification du mot de passe'
                ]]
        ]);
        
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control my-2',
                    'placeholder' => 'Votre email'
                ]
            ]);

        if($options['roles']) {
            
            $builder
                ->add('roles', ChoiceType::class, [
                    'multiple' => true,
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN'
                    ],
                    'empty_data' => 'ROLE_USER'
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'create' => true,
            'roles' => false
        ]);
    }
}
