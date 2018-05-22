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
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Provisioning;

use AppserverIo\Appserver\Provisioning\Steps\AbstractStep;

/**
 * An step implementation that creates a database, login credentials and dummy
 * products based on the specified datasource.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 *
 * @Inject
 */
class PrepareDatabaseStep extends AbstractStep
{

    /**
     * The maximum number of retries.
     *
     * @var integer
     */
    const MAX_RETRIES = 5;
    
    /**
     * The user processor instance (a SFB instance).
     *
     * @var \AppserverIo\Apps\Example\Services\SchemaProcessor
     * @EnterpriseBean(name="SchemaProcessor")
     */
    protected $schemaProcessor;

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

        // initialize retry flag and counter
        $retry = true;
        $retryCount = 0;

        do {
            try {
                // log a message that provisioning starts
                \info('Now start to prepare database using SchemaProcessor!');

                // create schema, default products + login credentials
                $this->schemaProcessor->createDatabase();
                $this->schemaProcessor->createSchema();
                $this->schemaProcessor->createDefaultProducts();
                $this->schemaProcessor->createDefaultCredentials();

                // log a message that provisioning has been successfull
                \info('Successfully prepared database using SchemaProcessor!');

                // don't retry, because step has been successful
                $retry = false;
            } catch (\Exception $e) {
                // raise the retry count
                $retryCount++;
                // query whether or not we've reached the maximum retry count
                if ($retryCount < PrepareDatabaseStep::MAX_RETRIES) {
                    // sleep for an increasing number of seconds
                    sleep($retryCount + 1);
                    // debug log the exeception
                    \debug(
                        sprintf(
                            'Failed %d (of %d) times to run provisioning step %s',
                            $retryCount,
                            PrepareDatabaseStep::MAX_RETRIES,
                            __CLASS__
                        )
                    );
                } else {
                    // log a message and stop retrying
                    \error($e->__toString());
                    $retry = false;
                }
            }
        } while ($retry);
    }
}
