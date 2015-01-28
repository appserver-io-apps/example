<?php

/**
 * AppserverIo\Apps\Example\MessageBeans\ImportChunkReceiver
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
use AppserverIo\Apps\Example\Entities\Sample;
use AppserverIo\Appserver\Naming\InitialContext;
use AppserverIo\Messaging\AbstractMessageListener;

/**
 * An message receiver that imports data chunks into a database.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @MessageDriven
 */
class ImportChunkReceiver extends AbstractMessageListener
{

    /**
     * The proxy class we need to connect to the persistence container.
     *
     * @var string
     */
    const PROXY_CLASS = 'SampleProcessor';

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

        error_log(print_r($sessionId, true));

        // log a message that the message we now process the passed chunk
        $this->getApplication()->getInitialContext()->getSystemLogger()->info('Process chunked data message');

        // create an initial context instance and inject the servlet request
        $initialContext = new InitialContext();
        $initialContext->injectApplication($this->getApplication());

        // lookup and return the requested bean proxy
        $processor = $initialContext->lookup(ImportChunkReceiver::PROXY_CLASS);

        // read in message chunk data
        $chunkData = $message->getMessage();

        // import the data found in the file
        foreach ($chunkData as $data) {
            // explode the name parts and append the data in the database
            list ($firstname, $lastname) = explode(',', $data);

            // prepare the entity
            $entity = new Sample();
            $entity->setName(trim($firstname . ', ' . $lastname));

            // store the entity in the database
            $processor->persist($entity);
        }

        // update the message monitor for this message
        $this->updateMonitor($message);
    }
}
