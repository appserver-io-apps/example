<?php

/**
 * AppserverIo\Apps\Example\Actions\LoginAction
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

use AppserverIo\Http\HttpProtocol;
use AppserverIo\Routlt\BaseAction;
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Routlt\Util\Validateable;
use AppserverIo\Apps\Example\Utils\ViewHelper;
use AppserverIo\Apps\Example\Utils\ProxyKeys;
use AppserverIo\Apps\Example\Utils\ContextKeys;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Apps\Example\Utils\SessionKeys;
use AppserverIo\Apps\Example\Exceptions\LoginException;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example servlet implementation that validates passed user credentials against
 * persistence container proxy and stores the user data in the session.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Path(name="/logout")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/login.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/login.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class LogoutAction extends BaseAction
{

    /**
     * Action that destroys the session and log the user out.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function perform(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // destroy the session and reset the cookie
        if ($session = ViewHelper::singleton()->getLoginSession($servletRequest)) {
            $session->destroy('Explicit logout requested by: ' . ViewHelper::singleton()->getUsername($servletRequest));
        }

        // action invocation has been successfull
        return ActionInterface::INPUT;
    }
}
