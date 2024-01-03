<?php

namespace App\Form;

use App\Entity\Comment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'votre commentaire',
                'attr' => [
                    'rows' => 5,
                ],
            ])
            ->add('rate', IntegerType::class, [
                'label' => 'note de 1 à 5',
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'minMessage' => 'La note doit être au minimum {{ limit }}',
                        'maxMessage' => 'La note doit être au maximum {{ limit }}',
                    ]),
                ],
                'attr' => [
                    'rows' => 5,
                ],
            ])
//            ->add('author')
//            ->add('episode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}