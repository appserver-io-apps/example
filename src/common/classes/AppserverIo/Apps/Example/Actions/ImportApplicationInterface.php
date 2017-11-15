<?php

/**
 * AppserverIo\Apps\Example\Actions\ImportApplicationInterface
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

namespace AppserverIo\Apps\Example\Actions;

use TechDivision\Import\ApplicationInterface;

/**
 * The interface for the M2IF - appserver.io application implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
interface ImportApplicationInterface extends ApplicationInterface
{

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication();

    /**
     * Return's the plugin factory instance.
     *
     * @return \TechDivision\Import\Plugins\PluginFactoryInterface The plugin factory instance
     */
    public function getPluginFactory();
}
