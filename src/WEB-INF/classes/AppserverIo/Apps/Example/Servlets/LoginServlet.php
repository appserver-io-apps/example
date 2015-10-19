<?php

/**
 * AppserverIo\Apps\Example\Servlets\LoginServlet
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

namespace AppserverIo\Apps\Example\Servlets;

use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use AppserverIo\Apps\Example\Utils\SessionKeys;

/**
 * Demo servlet handling a login GET request to test SFSB functionality.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class LoginServlet extends HttpServlet
{

    /**
     * The user processor instance (a SFB instance).
     *
     * @var \AppserverIo\Apps\Example\Services\UserProcessor
     * @EnterpriseBean(name="UserProcessor")
     */
    protected $userProcessor;

    /**
     * Handles a HTTP GET request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
     * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doGet()
     */
    public function doGet(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if we've a user logged into the system
        if ($this->userProcessor->getUserViewDataOfLoggedIn() == null) {
            // start the session, because we need a session-ID for our stateful session bean
            $session = $this->getLoginSession($servletRequest, true);
            $session->start();

            // try to login, using the session bean
            $this->userProcessor->login('appserver', 'appserver.i0');

            // if successfully then add the username to the session and redirect to the overview
            $session->putData(SessionKeys::USERNAME, 'appserver');

            // log that we've succussfully been logged into the system
            $servletRequest
                ->getContext()
                ->getInitialContext()
                ->getSystemLogger()
                ->info('Successfully logged in with appserver/appserver.i0!');
        }

        // append the Hello World! to the body stream
        $servletResponse->appendBodyStream('Logged-In as ' . $this->userProcessor->getUserViewDataOfLoggedIn()->getUsername());
    }

    /**
     * Returns the session with the passed session name.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface $servletRequest The request instance
     * @param boolean                                                   $create         TRUE if a session has to be created if we can't find any
     *
     * @return \AppserverIo\Psr\Servlet\Http\HttpSessionInterface|null The requested session instance
     * @throws \Exception Is thrown if we can't find a request instance
     */
    public function getLoginSession(HttpServletRequestInterface $servletRequest, $create = false)
    {

        // try to load the servlet request
        if ($servletRequest == null) {
            throw new \Exception('Can\'t find necessary servlet request instance');
        }

        // return the session
        return $servletRequest->getSession($create);
    }
}
