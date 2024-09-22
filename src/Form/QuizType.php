<?php

namespace App\Form;

use App\Entity\Quiz;

use Symfony\Component\Form\AbstractType;
use App\Entity\Course;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('question', TextType::class, [
            'label' => 'Question du quiz',
        ])
        ->add('answer', TextType::class, [
            'label' => 'Réponse correcte',
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}

