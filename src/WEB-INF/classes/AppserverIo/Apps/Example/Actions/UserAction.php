<?php

/**
 * AppserverIo\Apps\Example\Actions\UserAction
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

use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Apps\Example\Utils\ViewHelper;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example action implementation that loads data over a persistence container proxy
 * and renders a list, based on the returned values.
 *
 * Additional it provides functionality to edit, delete und persist the data of the
 * user actually logged into the system.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Path(name="/user")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/user.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/user.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class UserAction extends DispatchAction
{

    /**
     * The UserProcessor instance to handle the user functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\UserProcessor
     * @EnterpriseBean
     */
    protected $userProcessor;

    /**
     * Returns the ImportProcessor instance to handle the user functionality.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance
     */
    public function getUserProcessor()
    {
        return $this->userProcessor;
    }

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Loads the data of the user actually logged into the system and attaches it to the servlet
     * context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/index")
     */
    public function indexAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // load the data of the user actually logged into the system
        $servletRequest->setAttribute(RequestKeys::VIEW_DATA, $this->getUserProcessor()->getUserViewData(ViewHelper::singleton()->getUsername($servletRequest)));
    }

    /**
     * Invoked if the uses has to be saved.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/save")
     */
    public function saveAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // add a message, that the save action is not yet implemented
        $servletRequest->setAttribute(RequestKeys::ERROR_MESSAGES, array('The saveAction() method is not yet implemented!'));

        // load the data of the user actually logged into the system
        $servletRequest->setAttribute(RequestKeys::VIEW_DATA, $this->getUserProcessor()->getUserViewData(ViewHelper::singleton()->getUsername($servletRequest)));
    }
}
