<?php

/**
 * AppserverIo\Apps\Example\Servlets\AnnotatedServlet
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

use AppserverIo\Messaging\IntegerMessage;
use AppserverIo\Psr\Servlet\ServletConfigInterface;
use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Annotated servlet handling GET/POST requests.
 *
 * The GET requests only append the servlet name, defined in the @Route annotation,
 * to the response, whereas the POST requests send a message to the MQ, defined in
 * the queueSender property of the servlet.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Route(name="annotated",
 *        displayName="I'm the AnnotatedServlet",
 *        description="A annotated servlet implementation.",
 *        urlPattern={"/annotated.do", "/annotated.do*"},
 *        initParams={{"duration", "60000000"}})
 */
class AnnotatedServlet extends HttpServlet
{

    /**
     * Name of the initialization parameter with the duration.
     *
     * @var string
     */
    const DURATION = 'duration';

    /**
     * The duration in microseconds up from when, when we want to invoke the timer.
     *
     * @var integer
     */
    protected $duration = 0;

    /**
     * The singleton session bean instance.
     *
     * @var \AppserverIo\Apps\Example\Services\ASingletonProcessor
     * @EnterpriseBean
     */
    protected $aSingletonProcessor;

    /**
     * The queue session to send a message with.
     *
     * @var \AppserverIo\Messaging\QueueSession
     * @Resource(name="createASingleActionTimer", type="pms/createASingleActionTimer")
     */
    protected $queueSender;

    /**
     * The system logger implementation.
     *
     * @var \AppserverIo\Logger\Logger
     * @Resource(lookup="php:global/log/System")
     */
    protected $systemLogger;

    /**
     * Initializes the servlet with the passed configuration.
     *
     * @param \AppserverIo\Psr\Servlet\ServletConfigInterface $servletConfig The configuration to initialize the servlet with
     *
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the configuration has errors
     * @return void
     * @see \AppserverIo\Psr\Servlet\GenericServlet::init()
     */
    public function init(ServletConfigInterface $servletConfig)
    {

        // call parent method
        parent::init($servletConfig);

        // initialize the duration with the value found in the annotation
        $this->duration = (integer) $servletConfig->getInitParameter(AnnotatedServlet::DURATION);
    }

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
                'Invoked counter of SSB to %d (%s)',
                $this->aSingletonProcessor->raiseCounter(),
                $this->getServletConfig()->getServletName()
            )
        );

        // try to load the default user (for dummy purposes only)
        $this->aSingletonProcessor->loadUser();

        // add the servlet name to the response
        $servletResponse->appendBodyStream($this->getServletConfig()->getServletName());
    }

    /**
     * Handles a HTTP POST request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
     * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doGet()
     */
    public function doPost(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        $this->queueSender->send(new IntegerMessage($this->duration), false);
    }
}
