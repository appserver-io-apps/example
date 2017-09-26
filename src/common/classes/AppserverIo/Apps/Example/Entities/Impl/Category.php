<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\Category
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Entities\Impl;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppserverIo\Apps\Example\Entities\AbstractEntity;

/**
 * Doctrine entity that represents a category.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @Gedmo\Tree(type="nested")
 */
class Category extends AbstractEntity
{

    /**
     * The category ID.
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * The catgory's left node.
     *
     * @var integer
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="left_node", type="integer", nullable=false)
     */
    private $leftNode;

    /**
     * The category's right node.
     *
     * @var integer
     * @Gedmo\TreeRight
     * @ORM\Column(name="right_node", type="integer", nullable=false)
     */
    private $rightNode;

    /**
     * The category's level.
     *
     * @var integer
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="level", type="integer", nullable=false)
     */
    private $level;

    /**
     * The category's title.
     *
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * The category's description.
     *
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * The collection with the category's children.
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Category", mappedBy="parent", cascade={"persist"}, fetch="EAGER")
     * @ORM\OrderBy({"leftNode"="ASC"})
     */
    private $children;

    /**
     * The root category.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Category
     *
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * The parent category.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Category
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Category", inversedBy="children", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    private $parent;

    /**
     * Used locale to override Translation listener`s locale.
     *
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * Initializes the category.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Set's the translatable locale.
     *
     * @param string $locale To locale
     *
     * @return void
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Return's the ID of the category's left node.
     *
     * @return integer The ID of the category's left node
     */
    public function getLeftNode()
    {
        return $this->leftNode;
    }

    /**
     * Set's the ID fo the category's left node.
     *
     * @param integer $leftNode The ID of the category's left node
     *
     * @return void
     */
    public function setLeftNode($leftNode)
    {
        $this->leftNode = $leftNode;
    }

    /**
     * Return's the ID of the category's right node.
     *
     * @return integer The ID of the category's right node
     */
    public function getRightNode()
    {
        return $this->rightNode;
    }

    /**
     * Set's the ID of the category's right node.
     *
     * @param integer $rightNode The ID of the category's right node
     *
     * @return void
     */
    public function setRightNode($rightNode)
    {
        $this->rightNode = $rightNode;
    }

    /**
     * Return's the category's level.
     *
     * @return integer The category's level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set's the category's level.
     *
     * @param integer $level The category's level
     *
     * @return void
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Return's the category's title.
     *
     * @return string The category's title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set's the category's title.
     *
     * @param string $title The category's title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Return's the category's unique path.
     *
     * @return string The category's unique path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set's the category's title.
     *
     * @param string $title The category's unique path
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Return's the category's description.
     *
     * @return string The category's description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set's the category's description.
     *
     * @param string $description The category's description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Return's the category ID.
     *
     * @return integer The category ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the category's children.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Category The category's children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set's the category's children.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Category $children The catgory's children
     *
     * @return void
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * Return's the root node.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Category The root node
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set's the category's root node.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Category $root The category's root node
     * @return void
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * Return's the category's parent node
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Category The parent node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set's the category's parent node
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Category|null $parent The parent node
     * @return void
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
}
