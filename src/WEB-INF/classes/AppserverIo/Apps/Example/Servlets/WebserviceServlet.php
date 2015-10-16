<?php

/**
 * AppserverIo\Apps\Example\Servlets\WebserviceServlet
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

use AppserverIo\Http\HttpProtocol;
use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Provides functionality to handle all SOAP requests with test cases for calling
 * methods that result in a fatal error, a \SoapFault and a \Exception.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class WebserviceServlet extends HttpServlet
{

    /**
     * Constant for the 'action' parameter with the method name we want to invoke on our SOAP server.
     *
     * @var string
     */
    const PARAMETER_ACTION = 'action';

    /**
     * Constant for the 'log' parameter with the string we want to log.
     *
     * @var string
     */
    const PARAMETER_LOG = 'log';

    /**
     * This method handles a GET request, that will return the WSDL output of the extended mock
     * webservice implementation.
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

        try {
            // check the action that we've to invoke
            if ($servletRequest->getParameter(WebserviceServlet::PARAMETER_ACTION)) {
                $action = $servletRequest->getParameter(WebserviceServlet::PARAMETER_ACTION);
            } else {
                $action = 'logSomething';
            }

            // create a SOAP client and make a call
            $client = new \SoapClient(null, array('location' => 'http://127.0.0.1:9080/example/webservice.do', 'uri' => 'http://test-uri/'));

            // invoke the requested method
            $response = $client->$action($servletRequest->getParameterMap());

            // append the data to the response
            $servletResponse->appendBodyStream($response);

        } catch (\Exception $e) {
            // handle the exception
            $servletResponse->appendBodyStream($e->__toString());
            $servletResponse->setStatusCode(500);
        }
    }

    /**
     * This method handles a POST request, that will handle the incoming SOAP call.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
     * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doPost()
     */
    public function doPost(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            // initialize the SOAP server that handles the request
            $soapServer = new \SoapServer(null, array('uri' => 'http://test-uri/'));
            $soapServer->setObject($this);

            // handle the request
            ob_start();
            $soapServer->handle($servletRequest->getBodyContent());
            $soapResponse = ob_get_clean();

            // append the SOAP response
            $servletResponse->appendBodyStream($soapResponse);
            $servletResponse->addHeader(HttpProtocol::HEADER_CONNECTION, 'close');

        } catch (\Exception $e) {
            // handle the exception
            $servletResponse->appendBodyStream($e->__toString());
            $servletResponse->setStatusCode(500);
        }
    }

    /**
     * Dummy method that'll will be invoked by a SOAP call.
     *
     * @param array $parameters The actual request parameters
     *
     * @return void
     */
    public function logSomething(array $parameters)
    {
        if (isset($parameters[WebserviceServlet::PARAMETER_LOG])) {
            return $parameters[WebserviceServlet::PARAMETER_LOG];
        } else {
            return sprintf('Parameter %s not specified', WebserviceServlet::PARAMETER_LOG);
        }
    }

    /**
     * Calling this method results in a fatal error.
     *
     * @return void
     */
    public function fatalError()
    {
        $this->unknownMethod();
    }

    /**
     * Calling this method results in an \Exception.
     *
     * @return void
     * @throws \Exception Is always thrown
     */
    public function throwException()
    {
        throw new \Exception(__METHOD__);
    }

    /**
     * Calling this method results in a \SoapFault.
     *
     * @return void
     * @throws \SoapFault Is always thrown
     */
    public function throwSoapFault()
    {
        throw new \SoapFault(__METHOD__, __LINE__);
    }
}
