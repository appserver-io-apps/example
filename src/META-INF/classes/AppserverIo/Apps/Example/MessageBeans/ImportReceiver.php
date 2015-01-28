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
use AppserverIo\Messaging\MessageQueue;
use AppserverIo\Messaging\ArrayMessage;
use AppserverIo\Messaging\Utils\PriorityMedium;
use AppserverIo\Messaging\QueueConnectionFactory;
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

        // define the import file from message
        $importFile = $message->getMessage();

        // open the import file
        $importData = file($importFile);

        // load the application name
        $applicationName = $this->getApplication()->getName();

        // initialize the connection and the session
        $queue = MessageQueue::createQueue('pms/importChunk');
        $connection = QueueConnectionFactory::createQueueConnection($applicationName);
        $session = $connection->createQueueSession();
        $sender = $session->createSender($queue);

        // init chunk data
        $chunkSize = 100;

        // if data contains less entries than chunk size
        if (sizeof($importData) <= $chunkSize) {
            return $sender->send(new ArrayMessage($importData), false);
        }

        // prepare the variables we need for chunking
        $i = 0;
        $currentChunkIndex = 0;
        $chunkData = array();

        // send chunk message
        foreach ($importData as $data) {
            // increase counter
            $i ++;

            // fill chunk data array
            $chunkData[] = $data;

            // check if chunk size is reached
            if ($i == $chunkSize) {
                // raise the counter for the chunks
                $currentChunkIndex ++;

                // reset counter
                $i = 0;

                // send chunked data message
                $message = new ArrayMessage($chunkData);
                $message->setPriority(PriorityMedium::get());
                $send = $sender->send($message, false);

                // reset chunk data
                $chunkData = array();

                // reduce CPU load a bit
                usleep(10000); // === 0.01 s
            }
        }

        // update the message monitor for this message
        $this->updateMonitor($message);
    }
}
