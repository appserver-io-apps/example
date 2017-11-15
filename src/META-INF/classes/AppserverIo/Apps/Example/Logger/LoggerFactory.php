<?php

/**
 * AppserverIo\Apps\Example\Logger\LoggerFactory
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

namespace AppserverIo\Apps\Example\Logger;

use TechDivision\Import\Utils\LoggerKeys;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The logger factory implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class LoggerFactory extends \TechDivision\Import\Cli\Logger\LoggerFactory
{

    /**
     * The system logger implementation.
     *
     * @var \AppserverIo\Logger\Logger
     * Resource(lookup="php:global/log/System")
     */
    protected $systemLogger;

    /**
     * The configuration with the data to create the loggers with.
     *
     * @var \TechDivision\Import\ConfigurationInterface
     * Inject(name="Configuration")
     */
    protected $configuration;

    /**
     * Create's and return's the loggers to use.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The array with the initialized loggers
     */
    public function factory()
    {

        // initialize the collection for the loggers
        $loggers = new ArrayCollection();

        // add it to the array
        $loggers->set(LoggerKeys::SYSTEM, $this->systemLogger);

        // append the configured loggers or override the default one
        foreach ($this->configuration->getLoggers() as $loggerConfiguration) {
            // load the factory class that creates the logger instance
            $loggerFactory = $loggerConfiguration->getFactory();
            // create the logger instance and add it to the available loggers
            $loggers->set($loggerConfiguration->getName(), $loggerFactory::factory($this->configuration, $loggerConfiguration));
        }

        // return the collection with the initialized loggers
        return $loggers;
    }
}
