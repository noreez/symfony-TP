<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Video;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add(
                'createdAt',
                DateTimeType::class,
                array(
                    'format' => 'YYYY-MM-dd HH:mm',
                    'attr' => array('data-date-format' => 'YYYY-MM-DD HH:mm', 'readonly' => true)
                )
            )
            ->add('published')
            ->add('url')
            ->add('description', TextareaType::class, array('required' => false))
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
            ])
            ->add('submit', SubmitType::class) // Add a submit button
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
