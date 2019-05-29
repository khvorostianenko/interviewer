<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }
    
    public function findAllChildCategoryIds(int $categoryId): array
    {
        /** @var $category Category */
        $category = $this->find($categoryId);
        $result = [$category->getId()];
        
        /** @var $child Category */
        foreach ($category->getChildren() as $child) {
            $result = array_merge($result, $this->findAllChildCategoryIds($child->getId()));
        }
        
        return $result;
    }
}
