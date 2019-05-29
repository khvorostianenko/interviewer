<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Question;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'description',
                'query_builder' => function(CategoryRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('category');
                
                    if (!empty($options["category_id"])) {
                        $qb
                            ->andWhere('category.id = :id')
                            ->setParameter('id', $options["category_id"]);
                    }
                
                    
                    // the function returns a QueryBuilder object
                    return $qb
                        // find all categories where parent IS NOT NULL
                        ->andWhere('category.parent IS NOT NULL')
                        ->orderBy('category.id', 'ASC');
                },
            ])
            ->add('caption', TextareaType::class)
            ->add('answerOfficial', TextareaType::class)
            ->add('enabled', CheckboxType::class, ['required' => false])
            ->add('learned', CheckboxType::class, ['required' => false])
            ->add('submit', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'category_id' => null,
        ]);
    }
}
