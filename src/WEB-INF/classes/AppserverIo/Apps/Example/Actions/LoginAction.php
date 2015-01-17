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
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Actions;

use AppserverIo\Apps\Example\Utils\ContextKeys;
use AppserverIo\Psr\Servlet\Http\HttpServletRequest;
use AppserverIo\Psr\Servlet\Http\HttpServletResponse;
use AppserverIo\Apps\Example\Exceptions\LoginException;
use AppserverIo\Apps\Example\Utils\ProxyKeys;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Apps\Example\Utils\SessionKeys;

/**
 * Example servlet implementation that validates passed user credentials against
 * persistence container proxy and stores the user data in the session.
 *
 * @category   Appserver
 * @package    TechDivision_ApplicationServerExample
 * @subpackage Actions
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io
 */
class LoginAction extends ExampleBaseAction
{

    /**
     * The relative path, up from the webapp path, to the template to use.
     *
     * @var string
     */
    const LOGIN_TEMPLATE = 'static/templates/login.phtml';

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Loads all sample data and attaches it to the servlet context ready to be rendered
     * by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequest  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponse $servletResponse The response instance
     *
     * @return void
     */
    public function indexAction(HttpServletRequest $servletRequest, HttpServletResponse $servletResponse)
    {
        $viewData = $this->getProxy(ProxyKeys::USER_PROCESSOR)->checkForDefaultCredentials();
        $this->setAttribute(ContextKeys::VIEW_DATA, $viewData);
        $servletResponse->appendBodyStream($this->processTemplate(LoginAction::LOGIN_TEMPLATE, $servletRequest, $servletResponse));
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequest  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponse $servletResponse The response instance
     *
     * @return void
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function loginAction(HttpServletRequest $servletRequest, HttpServletResponse $servletResponse)
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
            $session = $this->getLoginSession(true);
            $session->start();

            // try to login, using the session bean
            $this->getProxy(ProxyKeys::USER_PROCESSOR)->login($username, $password);

            // if successfully then add the username to the session and redirect to the overview
            $session->putData(SessionKeys::USERNAME, $username);

        } catch (LoginException $e) {
            // invalid login credentials
            $this->setAttribute(ContextKeys::ERROR_MESSAGES, array("Username or Password invalid"));
        } catch (\Exception $e) {
            // if not add an error message
            $this->setAttribute(ContextKeys::ERROR_MESSAGES, array($e->getMessage()));
        }

        // reload all entities and render the dialog
        $this->indexAction($servletRequest, $servletResponse);
    }

    /**
     * Action that destroys the session and log the user out.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequest  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponse $servletResponse The response instance
     *
     * @return void
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function logoutAction(HttpServletRequest $servletRequest, HttpServletResponse $servletResponse)
    {

        // destroy the session and reset the cookie
        if ($session = $this->getLoginSession()) {
            $session->destroy('Explicit logout requested by: ' . $this->getUsername());
        }

        // reload all entities and render the dialog
        $this->indexAction($servletRequest, $servletResponse);
    }
}
