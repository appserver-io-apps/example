<?php

/**
 * AppserverIo\Apps\Example\Services\AbstractPersistenceProcessor
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

/**
 * Abstract processor implementation that provides a Doctrine ORM entity manager.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
abstract class AbstractPersistenceProcessor extends AbstractProcessor
{

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * @PersistenceUnit(unitName="ExampleEntityManager")
     */
    protected $entityManager;

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
     * Close the entity manager's connection before destroying the bean.
     *
     * @return void
     * @PreDestroy
     */
    public function preDestroy()
    {
        if ($entityManager = $this->getEntityManager()) {
            if ($connection = $entityManager->getConnection()) {
                $connection->close();
            }
        }
    }

    /**
     * Close the entity manager's connection before re-attaching it to the container.
     *
     * @return void
     * @PreAttach
     */
    public function preAttach()
    {
        if ($entityManager = $this->getEntityManager()) {
            if ($connection = $entityManager->getConnection()) {
                $connection->close();
            }
        }
    }
}
