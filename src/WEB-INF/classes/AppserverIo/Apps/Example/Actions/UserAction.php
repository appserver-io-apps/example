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
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Apps\Example\Utils\ProxyKeys;
use AppserverIo\Apps\Example\Utils\ViewHelper;
use AppserverIo\Apps\Example\Utils\ContextKeys;
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
     * The UserProcessor instance to handle the login functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\UserProcessor
     * @EnterpriseBean
     */
    protected $userProcessor;

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

        try {
            // load the data of the user actually logged into the system
            $servletRequest->setAttribute(ContextKeys::VIEW_DATA, $this->userProcessor->getUserViewData(ViewHelper::singleton()->getUsername($servletRequest)));

        } catch (\Exception $e) {
            // if not add an error message
            $servletRequest->setAttribute(ContextKeys::ERROR_MESSAGES, array($e->getMessage()));
            // action invocation has failed
            return ActionInterface::FAILURE;
        }

        // action invocation has been successfull
        return ActionInterface::INPUT;
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
        try {
            // add a message, that the save action is not yet implemented
            $this->setAttribute(ContextKeys::ERROR_MESSAGES, array('The saveAction() method is not yet implemented!'));

            // load the data of the user actually logged into the system
            $servletRequest->setAttribute(ContextKeys::VIEW_DATA, $this->userProcessor->getUserViewData(ViewHelper::singleton()->getUsername($servletRequest)));

        } catch (\Exception $e) {
            // if not add an error message
            $servletRequest->setAttribute(ContextKeys::ERROR_MESSAGES, array($e->getMessage()));
            // action invocation has failed
            return ActionInterface::FAILURE;
        }

        // action invocation has been successfull
        return ActionInterface::INPUT;
    }
}
