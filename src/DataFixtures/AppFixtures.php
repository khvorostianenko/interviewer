<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const QUESTIONS_DATA = [
        [
            'caption' => "What is your name",
            'answer' => "Mikhail",
        ],
        [
            'caption' => "What is your surname",
            'answer' => "K",
        ],
        [
            'caption' => "Where do you live?",
            'answer' => "K",
        ]
    ];
    
    public function load(ObjectManager $manager)
    {
        $gereralCategory = new Category();
        $gereralCategory->setDescription("General category");

        $mainCategory = new Category();
        $mainCategory->setDescription("Main category");
        $gereralCategory->addChild($mainCategory);

        $childCategory = new Category();
        $childCategory->setDescription("Child category");
        $mainCategory->addChild($childCategory);
        
        $manager->persist($gereralCategory);
        
        foreach (self::QUESTIONS_DATA as $questionData) {
            $question = new Question();
            $question->setCaption($questionData['caption']);
            $question->setAnswerOfficial($questionData['answer']);
            $question->setCategory($childCategory);
    
            $manager->persist($question);
        }
    
        $manager->flush();
    }
}
