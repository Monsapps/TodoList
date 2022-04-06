<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control my-2',
                    'placeholder' => 'Le titre de la tâche'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Tâche',
                'row_attr' => [
                    'class' => 'form-floating my-2'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Le contenu de la tâche',
                    'style' => 'height: 200px;'
                ]
            ])
        ;
    }
}
