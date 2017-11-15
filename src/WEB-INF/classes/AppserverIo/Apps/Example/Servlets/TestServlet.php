<?php

/**
 * AppserverIo\Apps\Example\Servlets\TestServlet
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
 * Test servlet handling GET/POST requests.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Route(name="test",
 *        displayName="I'm the TestServlet",
 *        description="A test servlet implementation.",
 *        urlPattern={"/test.do", "/test.do*"})
 */
class TestServlet extends HttpServlet
{

    /**
     * The singleton session bean instance.
     *
     * @var \AppserverIo\Apps\Example\Services\TestProcessor
     * @EnterpriseBean
     */
    protected $testProcessor;

    /**
     * The system logger implementation.
     *
     * @var \AppserverIo\Logger\Logger
     * @Resource(lookup="php:global/log/System")
     */
    protected $systemLogger;

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

        // log a dummy message (to show that logger injection works)
        $this->systemLogger->error(
            sprintf(
                'Invoked doSomething() method of SFSB with result %s',
                $email = $this->testProcessor->doSomething()
            )
        );

        // add the servlet name to the response
        $servletResponse->appendBodyStream(sprintf('Found E-Mail: %s', $email));
    }
}
