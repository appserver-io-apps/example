<?php

/**
 * AppserverIo\Apps\Example\Services\ProductProcessor
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
 *
 * @Stateless
 */
class ProductProcessor extends AbstractPersistenceProcessor implements ProductProcessorInterface
{

    /**
     * Loads and returns the entity with the ID passed as parameter.
     *
     * @param integer $id The ID of the entity to load
     *
     * @return object The entity
     */
    public function load($id)
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->find('AppserverIo\Apps\Example\Entities\Impl\Product', $id);
    }

    /**
     * Persists the passed entity.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Product $entity The entity to persist
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Product The persisted entity
     */
    public function persist(Product $entity)
    {
        // load the entity manager
        $entityManager = $this->getEntityManager();
        // check if a detached entity has been passed
        if ($entity->getSampleId()) {
            $merged = $entityManager->merge($entity);
            $entityManager->persist($merged);
        } else {
            $entityManager->persist($entity);
        }
        // flush the entity manager
        $entityManager->flush();
        // and return the entity itself
        return $entity;
    }

    /**
     * Deletes the entity with the passed ID.
     *
     * @param integer $id The ID of the entity to delete
     *
     * @return array An array with all existing entities
     */
    public function delete($id)
    {

        // delete the entity with the passed ID
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entityManager->merge($this->load($id)));
        $entityManager->flush();

        // load and return all data
        return $this->findAll();
    }

    /**
     * Returns an array with all existing entities.
     *
     * @param integer $limit  The maxium number of rows to return
     * @param integer $offset The row to start with
     *
     * @return array An array with all existing entities
     */
    public function findAll($limit = 100, $offset = 0)
    {
        // load all entities
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\Product');
        return $repository->findBy(array(), array(), $limit, $offset);
    }
}
