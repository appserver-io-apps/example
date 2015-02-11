<?php

/**
 * AppserverIo\Apps\Example\MessageBeans\CreateASingleActionTimer
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

namespace AppserverIo\Apps\Example\MessageBeans;

use AppserverIo\Psr\Pms\MessageInterface;
use AppserverIo\Messaging\AbstractMessageListener;
use AppserverIo\Psr\EnterpriseBeans\TimerInterface;
use AppserverIo\Psr\EnterpriseBeans\TimedObjectInterface;

/**
 * This is the implementation of a message bean that simply creates and starts a single
 * action timer.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @MessageDriven
 */
class CreateASingleActionTimer extends AbstractMessageListener implements TimedObjectInterface
{

    /**
     * Will be invoked when a new message for this message bean will be available.
     *
     * @param \AppserverIo\Psr\Pms\MessageInterface $message   A message this message bean is listen for
     * @param string                                $sessionId The session ID
     *
     * @return void
     * @see \AppserverIo\Psr\Pms\MessageListenerInterface::onMessage()
     */
    public function onMessage(MessageInterface $message, $sessionId)
    {

        // load the timer service registry
        $timerServiceRegistry = $this->getApplication()->search('TimerServiceContextInterface');

        // load the timer service for this class -> that allows us to invoke the
        // CreateASingleActionTimer::timeout() method in the specified number of
        // milliseconds!
        $timerService = $timerServiceRegistry->lookup('CreateASingleActionTimer');

        // our single action timer should be invoked 60 seconds from now
        $duration = $message->getMessage();

        // we create a single action timer
        $timerService->createSingleActionTimer($duration);

        // log a message that the single action timer has been successfully created
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf('Successfully created a single action timer with a duration of %d microseconds', $duration)
        );

        // update the message monitor for this message
        $this->updateMonitor($message);
    }

    /**
     * Invoked by the container upon timer expiration.
     *
     * @param \AppserverIo\Psr\EnterpriseBeans\TimerInterface $timer Timer whose expiration caused this notification
     *
     * @return void
     **/
    public function timeout(TimerInterface $timer)
    {
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by interface', __METHOD__)
        );
    }
}
