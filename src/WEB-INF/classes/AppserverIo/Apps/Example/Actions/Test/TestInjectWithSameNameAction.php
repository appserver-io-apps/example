<?php

/**
 * AppserverIo\Apps\Example\Actions\Test\TestInjectWithSameNameAction
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
use AppserverIo\Routlt\Annotations as RLT;
use AppserverIo\Psr\EnterpriseBeans\Annotations as EPB;
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
 * @RLT\Path(
 *     name="/test/inject-with-same-name",
 *     results={
 *         @RLT\Result(name="input", result="input", type="RawResult")
 *     }
 * )
 *
 */
class TestInjectWithSameNameAction extends DispatchAction
{

    /**
     * The first bean reference.
     *
     * @var \AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation
     * @EPB\Inject(name="RandomBeanImplementation")
     */
    protected $beanImpl1;
    
    /**
     * The second bean reference.
     *
     * @var \AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation
     * @EPB\Inject(name="RandomBeanImplementation")
     */
    protected $beanImpl2;
    
    /**
     * The third bean reference.
     *
     * @var \AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation
     * @EPB\Inject(name="RandomBeanImplementation")
     */
    protected $beanImpl3;

    /**
     * Returns the first bean reference.
     *
     * @return \AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation The refrence
     */
    public function getBeanImpl1()
    {
        return $this->beanImpl1;
    }
    
    /**
     * Returns the second bean reference.
     *
     * @return \AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation The refrence
     */
    public function getBeanImpl2()
    {
        return $this->beanImpl2;
    }
    
    /**
     * Returns the third bean reference.
     *
     * @return \AppserverIo\Apps\Example\Actions\Test\RandomBeanImplementation The refrence
     */
    public function getBeanImpl3()
    {
        return $this->beanImpl3;
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
     * @return void
     *
     * @RLT\Action(name="/index")
     */
    public function indexAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        $servletResponse->appendBodyStream(
            $this->getBeanImpl2()->someMethod(
                $this->getBeanImpl3()->someMethod(
                    $this->getBeanImpl1()->someMethod('Test')
                )
            )
        );
    }
}
