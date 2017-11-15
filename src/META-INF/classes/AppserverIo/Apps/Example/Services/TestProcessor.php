<?php

/**
 * AppserverIo\Apps\Example\Services\TestProcessor
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
 * A test processor implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateful
 */
class TestProcessor extends AbstractPersistenceProcessor
{

    /**
     * The stateful session bean instance.
     *
     * @var \AppserverIo\Apps\Example\Services\AnotherProcessor
     * @EnterpriseBean
     */
    protected $anotherProcessor;

    /**
     * The randomizer instance.
     *
     * @var \AppserverIo\Apps\Example\Services\SomeTest
     * @Inject(type="\AppserverIo\Apps\Example\Services\SomeTest")
     */
    protected $someTest;

    /**
     * A randomizer instance injected by the deployment descriptor.
     *
     * @var \AppserverIo\Apps\Example\Services\SomeTest
     */
    protected $anotherTest;

    /**
     * Test method.
     *
     * @return string The result
     */
    public function doSomething()
    {

        if ($this->anotherTest) {
            error_log('Found anotherTest initialized!');
            return $this->anotherTest->randomizeString($this->anotherProcessor->doSomething());
        }

        error_log('Found anotherTest NOT initialized!');

        return $this->someTest->randomizeString($this->anotherProcessor->doSomething());
    }
}
