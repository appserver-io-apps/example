<?php

/**
 * AppserverIo\Apps\Example\Services\ASingletonProcessor
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

use AppserverIo\Psr\EnterpriseBeans\TimerInterface;
use AppserverIo\Psr\EnterpriseBeans\TimedObjectInterface;

/**
 * A dummy singleton session bean implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Singleton(name="ASingletonProcessor", description="A sample implementation for a singleton session bean")
 * @Startup
 */
class ASingletonProcessor extends \Stackable implements ASingletonProcessorInterface, TimedObjectInterface
{

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(type="ApplicationInterface")
     */
    protected $application;

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * @PersistenceUnit(unitName="ExampleEntityManager")
     */
    protected $entityManager;

    /**
     * The system logger implementation.
     *
     * @var \AppserverIo\Logger\Logger
     * @Resource(lookup="php:global/log/System")
     */
    protected $systemLogger;

    /**
     * A counter how often the instance has been invoked.
     *
     * @var integer
     */
    protected $counter = 0;

    /**
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @PostConstruct
     */
    public function initialize()
    {

        // initialize the counter
        $this->counter = 0;

        // log a message for the @PostConstruct method invokation
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostConstruct annotation %d times', __METHOD__, $this->raiseCounter())
        );
    }

    /**
     * Example method that will be invoked after the SSB has been detached from the container.
     *
     * @return void
     * @PostDetach
     */
    public function postDetach()
    {
        // log a message for the @PostDetach method invokation
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostDetach annotation', __METHOD__)
        );
    }

    /**
     * Close the entity manager's connection before re-attaching it to the container.
     *
     * @return void
     * @PreAttach
     */
    public function preAttach()
    {
        // log a message for the @PreAttach method invokation
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PreAttach annotation', __METHOD__)
        );
    }

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Return's the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManagerInterface The initialized Doctrine entity manager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Return's the system logger instance.
     *
     * @return \AppserverIo\Logger\Logger The sytsem logger instance
     */
    public function getSystemLogger()
    {
        return $this->systemLogger;
    }

    /**
     * Returns the initial context instance.
     *
     * @return \AppserverIo\Appserver\Application\Interfaces\ContextInterface The initial context instance
     */
    public function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
    }

    /**
     * Raises the invokation counter by one.
     *
     * @return integer Raises and returns the counter
     */
    public function raiseCounter()
    {
        return $this->counter++;
    }

    /**
     * Loads the default user for dummy purposes only.
     *
     * @return void
     */
    public function loadUser()
    {

        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\User');

        // try to load the user and log the found name
        if ($user = $repository->findOneBy(array('username' => 'appserver'))) {
            $this->getSystemLogger()->info(sprintf('Found user with username: %s', $user->getUsername()));
        }
    }

    /**
     * A dummy method invoked by the container upon timer schedule.
     *
     * @param TimerInterface $timer The timer instance
     *
     * @return void
     * @Schedule(dayOfMonth = EVERY, month = EVERY, year = EVERY, second = ZERO, minute = EVERY, hour = EVERY)
     */
    public function invokedByTimer(TimerInterface $timer)
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @Schedule annotation %d times', __METHOD__, $this->raiseCounter())
        );
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
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by interface %d times', __METHOD__, $this->raiseCounter())
        );
    }
}
