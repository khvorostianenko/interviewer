<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/{idCategory}/question")
 */
class AdminQuestionController extends AbstractController
{
    private $questionRepository;
    
    public function __construct(
        QuestionRepository $questionRepository
    )
    {
        $this->questionRepository = $questionRepository;
    }
    
    /**
     * @Route(
     *     "/add",
     *     name="question_add"
     * )
     */
    public function add(Request $request, int $idCategory)
    {
        $question = new Question();
        $questionForm = $this->createForm(QuestionType::class, $question, ['category_id' => $idCategory]);
        
        $questionForm->handleRequest($request);
        
        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            
            $entityManager->flush();
            
            return $this->redirectToRoute('index', ['id' => $idCategory]);
        }
        
        return $this->render(
            'question/question.add.html.twig',
            [
                'question_form' => $questionForm->createView(),
            ]
        );
    }
    
    /**
     * @Route("/edit/{id}", name="question_edit")
     */
    public function edit(Request $request, Question $question, int $idCategory)
    {
        $questionForm = $this->createForm(QuestionType::class, $question);
        $questionForm->handleRequest($request);
        
        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            
            return $this->redirectToRoute('index', ['id' => $idCategory]);
        }
        
        return $this->render(
            'question/question.edit.html.twig',
            ['question_form' => $questionForm->createView()]
        );
    }
    
    /**
     * @Route("/delete/{id}", name="question_delete")
     */
    public function delete(Question $question, int $idCategory)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($question);
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Success! Question deleted');
        
        return $this->redirectToRoute('index', ['id' => $idCategory]);
    }
    
    /**
     * @Route("/learned/{id}", name="question_learned")
     */
    public function learned(Question $question, int $idCategory)
    {
        $question->setLearned(true);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        
        return $this->redirectToRoute('index', ['id' => $idCategory]);
    }
}
