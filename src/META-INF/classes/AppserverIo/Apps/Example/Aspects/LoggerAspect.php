<?php

/**
 * AppserverIo\Apps\Example\Aspects\LoggerAspect
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Aspects;

use AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface;
use AppserverIo\Appserver\ServletEngine\RequestHandler;

/**
 * Aspect which allows for logging within the app's classes.
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Aspect
 */
class LoggerAspect
{

    /**
     * Pointcut which targets all index actions for all action classes
     *
     * @return null
     *
     * @Pointcut("call(\AppserverIo\Apps\Example\Actions\*->indexAction())")
     */
    public function allIndexActions()
    {
    }

    /**
     * Advice used to log the call to any advised method
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before("pointcut(allIndexActions())")
     */
    public function logInfoAdvice(MethodInvocationInterface $methodInvocation)
    {

        // load the application context
        $application = RequestHandler::getApplicationContext();

        // log that the method has been invoked
        $application->getInitialContext()
                    ->getSystemLogger()
                    ->info(sprintf('The method %s::%s is about to be called', $methodInvocation->getStructureName(), $methodInvocation->getName()));
    }
}
