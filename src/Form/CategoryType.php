<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'description',
                // Forbid to change the category when editing.
                // When creating category obtained from the route
                'query_builder' => function (CategoryRepository $repository) use ($options) {
                    if (is_null($options["category_id"])) {
                        return;
                    }
            
                    $qb = $repository->createQueryBuilder('category');
                    
                    return $qb
                        ->where('category.id = :id')
                        ->setParameter('id', $options["category_id"]);
            },
            ])
            ->add('description', TextType::class)
            ->add('submit', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'category_id' => null,
        ]);
    }
}