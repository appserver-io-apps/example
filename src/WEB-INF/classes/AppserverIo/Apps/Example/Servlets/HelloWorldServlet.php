<?php

/**
 * AppserverIo\Apps\Example\Servlets\HelloWorldServlet
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

/**
 * Demo servlet handling GET requests.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class HelloWorldServlet extends HttpServlet
{

    /**
     * The user processor instance.
     *
     * @var \AppserverIo\Apps\Example\Services\SampleProcessor
     * @EnterpriseBean(name="SampleProcessor")
     */
    protected $sampleProcessor;

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
        if ($loggedInUser = $this->userProcessor->getUserViewDataOfLoggedIn()) {
            $servletRequest->getContext()->getInitialContext()->getSystemLogger()->info(
                sprintf("Found user logged in: %s", $loggedInUser->getUsername())
            );
        }

        // log the number of samples found in the database
        $servletRequest->getContext()->getInitialContext()->getSystemLogger()->info(
            sprintf("Found %d samples", sizeof($this->sampleProcessor->findAll()))
        );

        // append the Hello World! to the body stream
        $servletResponse->appendBodyStream('Hello World!');
    }
}
