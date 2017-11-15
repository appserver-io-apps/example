<?php

/**
 * AppserverIo\Apps\Example\MessageBeans\ImportReceiver
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

/**
 * This is the implementation of a import message receiver.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @MessageDriven
 */
class ImportReceiver extends AbstractMessageListener
{

    /**
     * The ImportProcessor instance to handle the import functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\ImportProcessor
     * @EnterpriseBean
     */
    protected $importProcessor;

    /**
     * Returns the ImportProcessor instance to handle the sample funcionality.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance
     */
    public function getImportProcessor()
    {
        return $this->importProcessor;
    }

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

        // log a message that the message has successfully been received
        $this->getApplication()->getInitialContext()->getSystemLogger()->info('Successfully received / finished message');

        // update the message monitor for this message
        $this->updateMonitor($message);
    }
}
