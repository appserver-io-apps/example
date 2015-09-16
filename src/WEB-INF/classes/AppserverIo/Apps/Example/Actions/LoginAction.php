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

use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Routlt\ActionInterface;
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
 * @Path(name="/login")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/login.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/login.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class LoginAction extends DispatchAction
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
     * Loads all sample data and attaches it to the servlet context ready to be rendered
     * by the template.
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
        return ActionInterface::INPUT;
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     *
     * @Action(name="/login")
     */
    public function loginAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            // check if the necessary params has been specified and are valid
            if (($username = $servletRequest->getParameter(RequestKeys::USERNAME)) === null) {
                throw new \Exception(sprintf('Please enter a valid %s', RequestKeys::USERNAME));
            }

            // check if the necessary params has been specified and are valid
            if (($password = $servletRequest->getParameter(RequestKeys::PASSWORD)) === null) {
                throw new \Exception(sprintf('Please enter a valid %s', RequestKeys::PASSWORD));
            }

            // start the session, because we need a session-ID for our stateful session bean
            $session = ViewHelper::singleton()->getLoginSession($servletRequest, true);
            $session->start();

            // try to login, using the session bean
            $this->userProcessor->login($username, $password);

            // if successfully then add the username to the session and redirect to the overview
            $session->putData(SessionKeys::USERNAME, $username);

        } catch (LoginException $e) {
            // invalid login credentials
            $servletRequest->setAttribute(ContextKeys::ERROR_MESSAGES, array("Username or Password invalid"));
            // action invocation has failed
            return ActionInterface::FAILURE;
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
     * Action that destroys the session and log the user out.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     *
     * @Action(name="/logout")
     */
    public function logoutAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            // destroy the session and reset the cookie
            if ($session = ViewHelper::singleton()->getLoginSession($servletRequest)) {
                $session->destroy('Explicit logout requested by: ' . ViewHelper::singleton()->getUsername($servletRequest));
            }

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
