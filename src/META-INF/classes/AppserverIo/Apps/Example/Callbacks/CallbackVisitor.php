<?php

/**
 * AppserverIo\Apps\Example\Callbacks\CallbackVisitor
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

namespace AppserverIo\Apps\Example\Callbacks;

use Psr\Container\ContainerInterface;
use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Callbacks\CallbackVisitorInterface;

/**
 * Visitor implementation for a subject's callbacks.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class CallbackVisitor implements CallbackVisitorInterface
{

    /**
     * The DI container builder instance.
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * The constructor to initialize the instance.
     *
     * @param \Psr\Container\ContainerInterface $container The container instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Visitor implementation that initializes the observers of the passed subject.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject to initialize the observers for
     *
     * @return void
     */
    public function visit(SubjectInterface $subject)
    {
        // prepare the callbacks
        foreach ($subject->getCallbackMappings() as $type => $callbacks) {
            $this->prepareCallbacks($subject, $callbacks, $type);
        }
    }

    /**
     * Prepare the callbacks defined in the system configuration.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject   The subject to prepare the callbacks for
     * @param array                                          $callbacks The array with the callbacks
     * @param string                                         $type      The actual callback type
     *
     * @return void
     */
    public function prepareCallbacks(SubjectInterface $subject, array $callbacks, $type = null)
    {

        // iterate over the array with callbacks and prepare them
        foreach ($callbacks as $key => $callback) {
            // we have to initialize the type only on the first level
            if ($type == null) {
                $type = $key;
            }

            // query whether or not we've an subarry or not
            if (is_array($callback)) {
                $this->prepareCallbacks($subject, $callback, $type);
            } else {
                $subject->registerCallback($this->container->get($callback), $type);
            }
        }
    }
}
