<?php

/**
 * AppserverIo\Apps\Example\Actions\Test\TestAction
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

namespace AppserverIo\Apps\Example\Actions\Test;

use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example action implementation that loads data over a persistence container proxy
 * and renders a list, based on the returned values.
 *
 * Additional it provides functionality to edit, delete und persist the data after
 * changing it.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Path(name="/testTest")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/index.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/index.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 *
 */
class TestAction extends DispatchAction
{

    /**
     * The CartProcessor instance to handle the sample functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\SampleProcessor
     * @EnterpriseBean
     */
    protected $sampleProcessor;

    /**
     * Returns the SampleProcessor instance to handle the sample funcionality.
     *
     * @return \AppserverIo\Apps\Example\Services\SampleProcessor The instance
     */
    public function getSampleProcessor()
    {
        return $this->sampleProcessor;
    }

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

        // append the sample data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getSampleProcessor()->findAll());
    }
}
