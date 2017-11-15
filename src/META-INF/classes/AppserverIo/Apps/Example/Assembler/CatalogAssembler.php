<?php

/**
 * AppserverIo\Apps\Example\Assembler\CatalogAssembler
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

namespace AppserverIo\Apps\Example\Assembler;

use Doctrine\ORM\EntityManagerInterface;
use AppserverIo\Apps\Example\Dtos\CatalogViewData;

/**
 * Assemler implementation for the catalog data.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Inject
 */
class CatalogAssembler
{

    /**
     * The slug of the default category.
     *
     * @var string
     */
    const DEFAULT_CATEGORY = 'root/default-category';

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Initializes the instance with the passed instances.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager The initialized Doctrine entity manager
     *
     * @PersistenceUnit(name="EntityManager", unitName="ExampleEntityManager")
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Return's the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManagerInterface The initialized Doctrine entity manager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Return's the catalog view data for the passed category.
     *
     * @param string  $slug   The category slug to return the catalog view data for
     * @param integer $limit  The maxium number of rows to return
     * @param integer $offset The row to start with
     *
     * @return \AppserverIo\Apps\Example\Dtos\CatalogViewData The DTO with the catalog view data
     */
    public function getCatalogViewData($slug, $limit = 100, $offset = 0)
    {

        // initialize the catalog view data
        $catalogViewData = new CatalogViewData();

        // try to load the category by the passed slug
        $category = $this->getEntityManager()
                         ->getRepository('AppserverIo\Apps\Example\Entities\Impl\Category')
                         ->findOneBySlug($slug ? $slug : CatalogAssembler::DEFAULT_CATEGORY);

        // set the selected category
        $catalogViewData->setSelectedCatgory($category);

        // load and set the products for the found category
        $catalogViewData->setProducts($category->getProducts()->slice($offset, $limit));

        // load the first level categories
        $firstLevel = $this->getEntityManager()
                           ->getRepository('AppserverIo\Apps\Example\Entities\Impl\Category')
                           ->children($category, true);

        // set the first level categories
        $catalogViewData->setCatgories($firstLevel);

        // return the initialized DTO
        return $catalogViewData;
    }
}
