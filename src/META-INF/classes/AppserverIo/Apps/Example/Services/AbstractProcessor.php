<?php

/**
 * AppserverIo\Apps\Example\Services\AbstractProcessor
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

use AppserverIo\Psr\Application\ApplicationInterface;

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
class AbstractProcessor
{

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * @PersistenceUnit(unitName="ExampleEntityManager")
     */
    protected $entityManager;

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * Injects the application into all extending instances.
     *
     * ATTENTION: Will only be used if you activate it in the epb.xml file!
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface $application The application instance
     *
     * @return void
     */
    public function injectApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @PostConstruct
     */
    public function postConstruct()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostConstruct annotation', __METHOD__)
        );
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @PreDestroy
     */
    public function preDestroy()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PreDestroy annotation', __METHOD__)
        );
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @PostDetach
     */
    public function postDetach()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostDetach annotation', __METHOD__)
        );
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @PreAttach
     */
    public function preAttach()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PreAttach annotation', __METHOD__)
        );
    }

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
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
     * Returns the initial context instance.
     *
     * @return \AppserverIo\Appserver\Core\InitialContext The initial context instance
     */
    public function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
    }

    /**
     * Return's the system configuration
     *
     * @return \AppserverIo\Configuration\Interfaces\NodeInterface The system configuration
     */
    public function getSystemConfiguration()
    {
        return $this->getInitialContext()->getSystemConfiguration();
    }

    /**
     * Return's the array with the datasources.
     *
     * @return array The array with the datasources
     */
    public function getDatasources()
    {
        return $this->getSystemConfiguration()->getDatasources();
    }
}
