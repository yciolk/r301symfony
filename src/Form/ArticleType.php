<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

//consulter la doc symfony forms partie rendering/processing
//missions pour le formulaire : créer un form dans le controller
//faire un formulaire fonctionnel pour la page de création
//et après faire un formulaire pour l'update
class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //syntaxe : add(nom, type (pas obligatoire), propriétés (pareil))
            ->add('titre', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class => form-label mt-2']]) 
            ->add('texte', TextareaType::class, ['attr' => ['class' => 'from-control'], 'label_attr' => ['class' => 'formcheck-label mt-2']]) 
            ->add('publie', CheckboxType::class, ['attr' => ['class' => 'form-check-input m-2'], 'label_attr' => ['class' => 'form-check-label mt-1'], 'required' => false])
            ->add('date', DateTimeType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'form-label mt-2'],'widget' => 'single_text'])
            ->add('confirm', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
