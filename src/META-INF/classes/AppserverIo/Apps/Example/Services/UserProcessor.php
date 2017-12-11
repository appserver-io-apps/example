<?php

/**
 * AppserverIo\Apps\Example\Services\UserProcessor
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

use AppserverIo\Apps\Example\Exceptions\LoginException;
use AppserverIo\Apps\Example\Exceptions\FoundInvalidUserException;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateful
 */
class UserProcessor extends AbstractPersistenceProcessor implements UserProcessorInterface
{

    /**
     * The user, logged into the system.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\User $user
     */
    protected $user;

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Psr\Di\ProviderInterface $provider
     * @Resource(type="ProviderInterface")
     */
    protected $provider;

    /**
     * Validates the passed username agains the password.
     *
     * @param string $username The username to login with
     * @param string $password The password that should match the username
     *
     * @return void
     * @throws \AppserverIo\Apps\Example\Exceptions\LoginException Is thrown if the user with the passed username doesn't exist or match the password
     */
    public function login($username, $password)
    {

        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\User');

        // try to load the user
        $user = $repository->findOneBy(array('username' => $username));
        if ($user == null) {
            throw new LoginException('Username or Password doesn\'t match');
        }

        // try to match the passwords
        if ($user->getPassword() !== md5($password)) {
            throw new LoginException('Username or Password doesn\'t match');
        }

        // store the user in the session
        $this->user = $user;
    }

    /**
     * Logout the user and removes the SFSB instance from the container.
     *
     * @return void
     * @Remove
     */
    public function logout()
    {
        $this->user = null;
    }

    /**
     * Returns the user actually logged into the system.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\User|null The user instance
     */
    public function getUserViewDataOfLoggedIn()
    {
        return $this->user;
    }

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
    public function getUserViewData($username)
    {

        // if we already have a user, compare the username
        if ($this->user != null && $this->user->getUsername() != $username) {
            throw new FoundInvalidUserException(sprintf('Username of user logged into the system doesn\'t match %s', $username));
        }

        // if no user has been loaded, try to load the user
        if ($this->user == null) {
            // load the entity manager and the user repository
            $entityManager = $this->getEntityManager();
            $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\User');

            // reload the user from the repository
            $this->user = $repository->findOneBy(array('username' => $username));

            // log a message that the data has been loaded from database
            $this->getInitialContext()->getSystemLogger()->info(
                sprintf('Successfully reloaded data from database in stateful session bean %s', __CLASS__)
            );

        } else {
            // log a message that the data has already been loaded
            $this->getInitialContext()->getSystemLogger()->info(
                sprintf('Successfully loaded data from stateful session bean instance %s', __CLASS__)
            );
        }

        // return the user instance
        return $this->user;
    }
}
