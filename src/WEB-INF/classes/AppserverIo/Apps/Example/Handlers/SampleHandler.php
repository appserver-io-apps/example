<?php

/**
 * AppserverIo\Apps\Example\Handlers\SampleHandler
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

namespace AppserverIo\Apps\Example\Handlers;

use AppserverIo\Apps\Example\Entities\Sample;

/**
 * This is a web socket handler that handles requests
 * related with samples.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class SampleHandler extends BaseHandler
{

    /**
     * Class name of the persistence container proxy that handles the data.
     *
     * @var string
     */
    const PROXY_CLASS = 'AppserverIo\Apps\Example\Services\SampleProcessor';

    /**
     * Persists the sample entity with the passed data.
     *
     * @param string $sampleId The ID to be persisted
     * @param string $name     The name to be persisted
     *
     * @return \AppserverIo\Apps\Example\Entities\Sample The persisted entity
     */
    public function persistAction($sampleId, $name)
    {
        // create a new entity and persist it
        $entity = new Sample();
        $entity->setSampleId((integer) $sampleId);
        $entity->setName($name);

        // store and return the entity
        return array($this->getProxy(self::PROXY_CLASS)->persist($entity));
    }

    /**
     * Returns all sample entities.
     *
     * @return array The array with the sample entities
     */
    public function overviewAction()
    {
        return $this->getProxy(self::PROXY_CLASS)->findAll();
    }
}
