<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="Category",
     *     mappedBy="parent",
     *     cascade={"persist"}
     * )
     */
    private $children;
    
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Category",
     *     inversedBy="children"
     * )
     * @ORM\JoinColumn(
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $parent;
    
    /**
     * @ORM\Column(
     *     type="string",
     *     length=100
     * )
     * @Assert\NotBlank()
     */
    private $description;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="Question",
     *     mappedBy="category"
     * )
     */
    private $question;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->question = new ArrayCollection();
    }
    
    /**
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @return Category[]
     */
    public function getChildren(): PersistentCollection
    {
        return $this->children;
    }
    
    public function addChild(Category $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }
    
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Question[]
     */
    public function getQuestion(): PersistentCollection
    {
        return $this->question;
    }
}
