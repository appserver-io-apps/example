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
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Servlets;

use AppserverIo\Psr\Servlet\ServletConfig;
use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequest;
use AppserverIo\Psr\Servlet\Http\HttpServletResponse;
use AppserverIo\MessageQueueClient\QueueSession;
use AppserverIo\Psr\MessageQueueProtocol\Messages\IntegerMessage;

/**
 * Annotated servlet handling GET/POST requests.
 *
 * The GET requests only append the servlet name, defined in the @Route annotation,
 * to the response, whereas the POST requests send a message to the MQ, defined in
 * the queueSender property of the servlet.
 *
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
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
     * The queue session to send a message with.
     *
     * @var \AppserverIo\MessageQueueClient\QueueSession
     * @Resource(name="pms/createASingleActionTimer")
     */
    protected $queueSender;

    /**
     * Initializes the servlet with the passed configuration.
     *
     * @param \AppserverIo\Psr\Servlet\ServletConfig $servletConfig The configuration to initialize the servlet with
     *
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the configuration has errors
     * @return void
     * @see \AppserverIo\Psr\Servlet\GenericServlet::init()
     */
    public function init(ServletConfig $servletConfig)
    {
        $this->duration = (integer) $servletConfig->getInitParameter(AnnotatedServlet::DURATION);
    }

    /**
     * Handles a HTTP GET request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequest  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponse $servletResponse The response instance
     *
     * @return void
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
     * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doGet()
     */
    public function doGet(HttpServletRequest $servletRequest, HttpServletResponse $servletResponse)
    {
        $servletResponse->appendBodyStream($this->getServletConfig()->getServletName());
    }

    /**
     * Handles a HTTP POST request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequest  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponse $servletResponse The response instance
     *
     * @return void
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
     * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doGet()
     */
    public function doPost(HttpServletRequest $servletRequest, HttpServletResponse $servletResponse)
    {
        $this->queueSender->send(new IntegerMessage($this->duration));
    }
}
