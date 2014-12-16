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
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Services;

use AppserverIo\Psr\Application\ApplicationInterface;
use AppserverIo\Psr\EnterpriseBeans\TimerInterface;
use AppserverIo\Psr\EnterpriseBeans\TimedObjectInterface;
use AppserverIo\Appserver\PersistenceContainer\TimerServiceContext;

/**
 * A dummy singleton session bean implementation.
 *
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
 *
 * @Singleton(name="ASingletonProcessor", mappedName="SingletonProcessor", description="A sample implementation for a singleton session bean")
 * @Startup
 */
class ASingletonProcessor extends \Stackable implements ASingletonProcessorInterface, TimedObjectInterface
{

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @PostConstruct
     */
    public function initialize()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostConstruct annotation', __METHOD__)
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
     * Returns the initial context instance.
     *
     * @return \AppserverIo\Appserver\Application\Interfaces\ContextInterface The initial context instance
     */
    public function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
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
            sprintf('%s has successfully been invoked by @Schedule annotation', __METHOD__)
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
            sprintf('%s has successfully been by interface', __METHOD__)
        );
    }
}
