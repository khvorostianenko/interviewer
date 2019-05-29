<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    private $categoryRepository;
    private $questionRepository;
    
    public function __construct(
        CategoryRepository $categoryRepository,
        QuestionRepository $questionRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->questionRepository = $questionRepository;
    }
    
    /**
     * @Route(
     *     "/{id}", 
     *     defaults={"id"=null}, 
     *     name="index"
     * )
     */
    public function index($id)
    {
        $parentCategoryCondition = is_null($id) ? ['parent' => null] : ['id' => $id];
        /** @var $parentCategory Category */
        $parentCategory = $this->categoryRepository->findOneBy($parentCategoryCondition);
        
        $childCategoryIds = $this->categoryRepository->findAllChildCategoryIds($parentCategory->getId());
        
        /** @var Question[] $allQuestions */
        $allQuestions = $this->questionRepository->findBy(['category' => $childCategoryIds], ['caption' => 'ASC']);

        $randomUnlearnedQuestion = $this->getRandomUnlearnedQuestion($allQuestions);
        
        return $this->render(
            'admin/index.html.twig',
            [
                'category' => $parentCategory,
                'all_questions' => $allQuestions,
                'random_unlearned_question' => $randomUnlearnedQuestion,
            ]
        );
    }
    
    private function getRandomUnlearnedQuestion(array $allQuestions): ?Question
    {
        $notLearnedQuestions = [];
        
        /** @var Question $question */
        foreach ($allQuestions as $question) {
            if ($question->isLearned() || !$question->isEnabled()) {
                continue;
            }
        
            $notLearnedQuestions[] = $question;
        }
    
        return !empty($notLearnedQuestions) ? $notLearnedQuestions[rand(0, (count($notLearnedQuestions) - 1))] : null;
    }
}
