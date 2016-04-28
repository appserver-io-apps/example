<?php

/**
 * AppserverIo\Apps\Example\Services\UserProcessorInterface
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
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
interface UserProcessorInterface
{

    /**
     * Validates the passed username agains the password.
     *
     * @param string $username The username to login with
     * @param string $password The password that should match the username
     *
     * @return void
     * @throws \AppserverIo\Apps\Example\Exceptions\LoginException Is thrown if the user with the passed username doesn't exist or match the password
     */
    public function login($username, $password);

    /**
     * Logout the user and removes the SFSB instance from the container.
     *
     * @return void
     */
    public function logout();

    /**
     * Returns the user actually logged into the system.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\User|null The user instance
     */
    public function getUserViewDataOfLoggedIn();

    /**
     * Returns the data of the user that has been logged into the system.
     *
     * This method is an example implementation on how you can use a stateful
     * session bean to temporary store session data.
     *
     * @param string $username The username of the user to return the data for
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\User The user logged into the system
     * @throws \AppserverIo\Apps\Example\Exceptions\FoundInvalidUserException Is thrown if no user has been logged into the system or the username doesn't match
     * @see \AppserverIo\Apps\Example\Services\UserProcessor::login()
     */
    public function getUserViewData($username);
}
