<?php

/**
 * AppserverIo\Apps\Example\Services\AnotherProcessor
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

namespace AppserverIo\Apps\Example\Services;

/**
 * Anotehr test processor implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateful
 */
class AnotherProcessor extends AbstractPersistenceProcessor
{

    /**
     * Test method.
     *
     * @return string The result
     */
    public function doSomething()
    {

        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\User');

        // try to load the user
        $user = $repository->findOneBy(array('username' => 'appserver'));

        // return the email of the user
        return $user->getEmail();
    }
}
