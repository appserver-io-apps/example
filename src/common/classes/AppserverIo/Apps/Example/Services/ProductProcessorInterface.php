<?php

/**
 * AppserverIo\Apps\Example\Services\ProductProcessorInterface
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

namespace AppserverIo\Apps\Example\Services;

use AppserverIo\Apps\Example\Entities\Impl\Product;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
interface ProductProcessorInterface
{

    /**
     * Loads and returns the entity with the ID passed as parameter.
     *
     * @param integer $id The ID of the entity to load
     *
     * @return object The entity
     */
    public function load($id);

    /**
     * Persists the passed entity.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Product $entity The entity to persist
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Product The persisted entity
     */
    public function persist(Product $entity);

    /**
     * Deletes the entity with the passed ID.
     *
     * @param integer $id The ID of the entity to delete
     *
     * @return array An array with all existing entities
     */
    public function delete($id);

    /**
     * Returns an array with all existing entities.
     *
     * @param integer $limit  The maxium number of rows to return
     * @param integer $offset The row to start with
     *
     * @return array An array with all existing entities
     */
    public function findAll($limit = 100, $offset = 0);
}
