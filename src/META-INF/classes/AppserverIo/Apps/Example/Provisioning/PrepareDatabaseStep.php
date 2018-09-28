<?php

/**
 * AppserverIo\Apps\Example\Provisioning\PrepareDatabaseStep
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

namespace AppserverIo\Apps\Example\Provisioning;

use AppserverIo\Provisioning\Steps\AbstractStep;
use AppserverIo\Psr\EnterpriseBeans\Annotations as EPB;

/**
 * An step implementation that creates a database, login credentials and dummy
 * products based on the specified datasource.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @EPB\Inject(shared=false)
 */
class PrepareDatabaseStep extends AbstractStep
{

    /**
     * The user processor instance (a SFB instance).
     *
     * @var \AppserverIo\Apps\Example\Services\SchemaProcessorInterface
     * @EPB\EnterpriseBean(name="SchemaProcessor")
     */
    protected $schemaProcessor;

    /**
     * Returns the schema processor instance.
     *
     * @return \AppserverIo\Apps\Example\Services\SchemaProcessorInterface
     */
    protected function getSchemaProcessor()
    {
        return $this->schemaProcessor;
    }

    /**
     * Executes the functionality for this step, in this case the execution of
     * the PHP script defined in the step configuration.
     *
     * @return void
     * @throws \Exception Is thrown if the script can't be executed
     * @see \AppserverIo\Appserver\Provisioning\Steps\StepInterface::execute()
     */
    public function execute()
    {
        
        // log a message that provisioning starts
        \info(sprintf('Now start to prepare database using "%s"', get_class($this->getSchemaProcessor())));

        // create schema, default products + login credentials
        $this->getSchemaProcessor()->createDatabase();
        $this->getSchemaProcessor()->createSchema();
        $this->getSchemaProcessor()->createDefaultProducts();
        $this->getSchemaProcessor()->createDefaultCredentials();

        // log a message that provisioning has been successfull
        \info(sprintf('Successfully prepared database using "%s"', get_class($this->getSchemaProcessor())));
    }
}
