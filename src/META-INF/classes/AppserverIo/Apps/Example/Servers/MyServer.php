<?php

/**
 * \AppserverIo\Apps\Example\Servers\MyServer
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

namespace AppserverIo\Apps\Example\Servers;

use AppserverIo\Server\Servers\MultiThreadedServer;
use AppserverIo\Server\Interfaces\ServerContextInterface;

/**
 * Testserver implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/appserver
 * @link      http://www.appserver.io
 */
class MyServer extends MultiThreadedServer
{

    /**
     * Constructs the server instance
     *
     * @param \AppserverIo\Server\Interfaces\ServerContextInterface $serverContext The server context instance
     */
    public function __construct(ServerContextInterface $serverContext)
    {
        error_log("TEST TEST TEST: " . __METHOD__ . ':' . __LINE__);
        parent::__construct($serverContext);
    }
}
