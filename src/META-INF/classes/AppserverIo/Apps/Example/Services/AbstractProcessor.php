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
use AppserverIo\Psr\EnterpriseBeans\Annotations as EPB;

/**
 * Abstract processor implementation that provides basic functionality.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
abstract class AbstractProcessor
{

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @EPB\Resource(type="ApplicationInterface")
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
     * @EPB\PostConstruct
     */
    public function postConstruct()
    {
        \info(sprintf('%s has successfully been invoked by @PostConstruct annotation', __METHOD__));
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @EPB\PreDestroy
     */
    public function preDestroy()
    {
        \info(sprintf('%s has successfully been invoked by @PreDestroy annotation', __METHOD__));
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @EPB\PostDetach
     */
    public function postDetach()
    {
        \info(sprintf('%s has successfully been invoked by @PostDetach annotation', __METHOD__));
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @EPB\PreAttach
     */
    public function preAttach()
    {
        \info(sprintf('%s has successfully been invoked by @PreAttach annotation', __METHOD__));
    }

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * Returns the initial context instance.
     *
     * @return \AppserverIo\Appserver\Core\InitialContext The initial context instance
     */
    protected function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
    }

    /**
     * Return's the system configuration
     *
     * @return \AppserverIo\Configuration\Interfaces\NodeInterface The system configuration
     */
    protected function getSystemConfiguration()
    {
        return $this->getInitialContext()->getSystemConfiguration();
    }

    /**
     * Return's the array with the datasources.
     *
     * @return array The array with the datasources
     */
    protected function getDatasources()
    {
        return $this->getSystemConfiguration()->getDatasources();
    }
}
