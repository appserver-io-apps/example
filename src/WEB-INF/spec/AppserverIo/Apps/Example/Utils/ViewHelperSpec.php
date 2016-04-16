<?php

/**
 * AppserverIo\Apps\Example\Utils\ViewHelper
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

namespace spec\AppserverIo\Apps\Example\Utils;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

/**
 * Context keys that are used to store data in a application context.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class ViewHelperSpec extends ObjectBehavior
{

    /**
     * Test's the logout link.
     *
     * @return void
     */
    public function it_returns_the_logout_link()
    {
        $this->beConstructedThrough('singleton');
        $this->getLogoutLink()->shouldReturn('index.do/logout');
    }
}
