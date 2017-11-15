<?php

/**
 * AppserverIo\Apps\Example\Plugins\PluginFactory
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

namespace AppserverIo\Apps\Example\Plugins;

use Psr\Container\ContainerInterface;
use TechDivision\Import\Plugins\PluginFactoryInterface;
use TechDivision\Import\Configuration\PluginConfigurationInterface;

/**
 * A generic plugin factory implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class PluginFactory implements PluginFactoryInterface
{

    /**
     * The DI container instance.
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Initialize the factory with the DI container instance.
     *
     * @param \Psr\Container\ContainerInterface $container The DI container instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Factory method to create new plugin instance.
     *
     * @param \TechDivision\Import\Configuration\PluginConfigurationInterface $pluginConfiguration The plugin configuration
     *
     * @return \TechDivision\Import\Plugins\PluginInterface The plugin instance
     */
    public function createPlugin(PluginConfigurationInterface $pluginConfiguration)
    {

        // load the plugin instance from the DI container and set the plugin configuration
        $pluginInstance = $this->container->get($pluginConfiguration->getId());
        $pluginInstance->setPluginConfiguration($pluginConfiguration);

        // return the plugin instance
        return $pluginInstance;
    }
}
