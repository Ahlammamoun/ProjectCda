<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // You can add fields relevant to the results, for example:
        $builder->add('comment', TextType::class, [
            'label' => 'Your Feedback',  // Label for the input
            'required' => false,           // Making it optional
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
