<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }
    
    public function resetProgressByCategories(array $categoryIds): void
    {
        $qb = $this->createQueryBuilder('question');
        
        $q = $qb->update(Question::class, 'question')
            ->set('question.learned', 0)
            ->where('question.category IN (:categoryIds)')
            ->setParameter('categoryIds', $categoryIds)
            ->getQuery();

        $q->execute();
    }
}
