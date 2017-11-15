<?php

/**
 * AppserverIo\Apps\Example\Dtos\CatalogViewData
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

namespace AppserverIo\Apps\Example\Dtos;

use Doctrine\Common\Collections\ArrayCollection;
use AppserverIo\Apps\Example\Entities\Impl\Category;

/**
 * DTO for the catalog view data.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class CatalogViewData
{

    /**
     * The selected category.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Category
     */
    protected $selectedCategory;

    /**
     * The products of the selected category.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $products;

    /**
     * The categories of the same level and their parents.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    /**
     * Set's the selected category.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Category $selectedCategory The selected category
     *
     * @return void
     */
    public function setSelectedCatgory(Category $selectedCategory)
    {
        $this->selectedCategory = $selectedCategory;
    }

    /**
     * Return's the selected category.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Category The selected category
     */
    public function getSelectedCategory()
    {
        return $this->selectedCategory;
    }

    /**
     * Set's the categories of the same level and their parents.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $categories The categories
     *
     * @return void
     */
    public function setCatgories(ArrayCollection $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Return's the categories of the same level and their parents.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set's the products of the selected category.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $products The products of the selected category
     *
     * @return void
     */
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products;
    }

    /**
     * Return's the products of the selected category.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The products of the selected category
     */
    public function getProducts()
    {
        return $this->products;
    }
}
