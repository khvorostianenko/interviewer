<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * Many Questions for one Category
     * @ORM\ManyToOne(
     *     targetEntity="Category",
     *     inversedBy="question"
     * )
     * @ORM\JoinColumn(
     *      name="category_id",
     *      referencedColumnName="id",
     *      onDelete="CASCADE",
     *      nullable=false
     * )
     */
    private $category;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $caption;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $answerOfficial;
    
    /**
     * @ORM\Column(
     *     type="boolean", 
     *     options={"default":true}
     * )
     */
    private $enabled = true;
    
    /**
     * @ORM\Column(
     *     type="boolean", 
     *     options={"default":false}
     * )
     */
    private $learned = false;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
    
    public function getCaption()
    {
        return $this->caption;
    }
    
    public function setCaption($caption): void
    {
        $this->caption = $caption;
    }
    
    public function getAnswerOfficial()
    {
        return $this->answerOfficial;
    }
    
    public function setAnswerOfficial($answerOfficial): void
    {
        $this->answerOfficial = $answerOfficial;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }
    
    public function isLearned()
    {
        return $this->learned;
    }
    
    public function setLearned($learned): void
    {
        $this->learned = $learned;
    }
}
