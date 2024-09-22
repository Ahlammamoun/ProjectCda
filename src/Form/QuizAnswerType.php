<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class QuizAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $questions = $options['questions'];

        // Vérifier que les questions existent et créer des champs correspondants
        if (!empty($questions)) {
            $builder->add('answers', CollectionType::class, [
                'entry_type' => TextType::class,  // Chaque réponse est un champ texte
                'entry_options' => [
                    'label' => false,  // On gère le label dans le template
                ],
                'allow_add' => false,  // Pas d'ajout dynamique
                'mapped' => false,  // Cette collection n'est pas mappée à une propriété directe
            ]);
        }
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
        $resolver->setRequired('questions');  // On s'assure que les questions sont passées dans les options
    }
}
