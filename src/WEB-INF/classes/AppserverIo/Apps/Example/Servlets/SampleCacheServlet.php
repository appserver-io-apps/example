<?php

/**
 * AppserverIo\Apps\Example\Servlets\SampleCacheServlet
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
use AppserverIo\Http\HttpProtocol;

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
 * @Route(name="sampleCache", urlPattern={ "/sampleCache.do", "/sampleCache.do*"}, initParams={})
 */
class SampleCacheServlet extends HttpServlet
{

    /**
     * The sample processor instance (a SLSB instance).
     *
     * @var AppserverIo\Apps\Example\Services\SampleProcessor
     * @EnterpriseBean(name="SampleProcessor")
     */
    protected $sampleProcessor;

    /**
     * The sample cache processor instance (a SSB instance).
     *
     * @var AppserverIo\Apps\Example\Services\SampleCacheProcessor
     * @EnterpriseBean(name="SampleCacheProcessor")
     */
    protected $sampleCacheProcessor;

    /**
     *
     * @param HttpServletRequestInterface $servletRequest
     * @param HttpServletResponseInterface $servletResponse
     * @return void
     */
    public function doGet(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        $slotId = (int) $servletRequest->getParameter('slotId', FILTER_SANITIZE_NUMBER_INT);

        $samples = [];

        if ($slotId > 0) {

            /*
            $cachedSamples = $this->sampleCacheProcessor->get($slotId);

            if ($cachedSamples !== null) {

                $servletRequest->getContext()->getInitialContext()->getSystemLogger()->info(
                    sprintf("Found cached samples for slot-ID %d", $slotId)
                );

                $samples = $cachedSamples;

            } else {
            */

                $servletRequest->getContext()->getInitialContext()->getSystemLogger()->info(
                    sprintf("Load samples for slot-ID %d", $slotId)
                );

                // we fake the slot filter here
                $filter = array();
                for ($i = $slotId; $i < $slotId + 10; $i++) {
                    $filter[] = $i;
                }

                $samples = $this->sampleProcessor->findByFilter($filter);
                $this->sampleCacheProcessor->set($slotId, $samples);
            // }
        }

        // append the samples to the body stream
        $servletResponse->addHeader(HttpProtocol::HEADER_CONTENT_TYPE, 'application/json');
        $servletResponse->appendBodyStream(json_encode($samples));
    }
}
