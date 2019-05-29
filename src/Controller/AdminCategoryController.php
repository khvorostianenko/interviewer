<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/{idCategory}/category")
 */
class AdminCategoryController extends AbstractController
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
     * @Route("/add", name="category_add")
     */
    public function add(Request $request, int $idCategory)
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category, ['category_id' => $idCategory]);
        $categoryForm->handleRequest($request);
    
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
        
            $entityManager->flush();
        
            return $this->redirectToRoute('index', ['id' => $idCategory]);
        }
    
        return $this->render(
            'category/category.add.html.twig',
            [
                'category_form' => $categoryForm->createView(),
            ]
        );
    }
    
    /**
     * @Route("/edit/{id}", name="category_edit")
     */
    public function edit(Request $request, Category $category, int $idCategory)
    {
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);
        
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('index', ['id' => $idCategory]);
        }
        
        return $this->render(
            'category/category.edit.html.twig',
            ['category_form' => $categoryForm->createView()]
        );
    }
    
    /**
     * @Route("/delete/{id}", name="category_delete")
     */
    public function delete(Category $category, int $idCategory)
    {
        if ($category->getChildren()->count() !== 0) {
            $this->addFlash('error', 'Can`t delete category which has child categories');
            
            return $this->redirectToRoute('index', ['id' => $idCategory]);
        }
        
        if ($category->getQuestion()->count() !== 0) {
            $this->addFlash('error', 'Can`t delete category which has question');
            
            return $this->redirectToRoute('index', ['id' => $idCategory]);
        } 
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);

        $entityManager->flush();

        $this->addFlash('success', 'Success! Category deleted'); 
        
        return $this->redirectToRoute('index', ['id' => $idCategory]);
    }
    
    /**
     * @Route("/reset/{id}", name="category_progress_reset")
     */
    public function reset(Category $category, int $idCategory)
    {
        $categoryIds = $this->categoryRepository->findAllChildCategoryIds($category->getId());
        $this->questionRepository->resetProgressByCategories($this->categoryRepository->findBy(['id' => $categoryIds]));
        
        return $this->redirectToRoute('index', ['id' => $idCategory]);
    }
}
