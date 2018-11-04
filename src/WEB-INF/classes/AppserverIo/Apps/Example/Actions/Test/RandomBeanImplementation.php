<?php

/**
 * AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation
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

namespace AppserverIo\Apps\Example\Actions\Test;

use AppserverIo\Psr\EnterpriseBeans\Annotations as EPB;

/**
 * A bean for testing purposes.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @EPB\Inject
 */
class RandomBeanImplementation
{

    /**
     * A test method that returns what has been passed.
     *
     * @param mixed $stuff The stuff that has to be returned
     *
     * @return mixed The passed stuff
     */
    public function someMethod($stuff)
    {
        return $stuff;
    }
}
