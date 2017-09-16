<?php

/**
 * AppserverIo\Apps\Example\Services\SomeTest
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
 * A DI testing class
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class SomeTest
{

    /**
     * The randomizer to use.
     *
     * @var \AppserverIo\Apps\Example\Services\Randomizer
     */
    protected $randomizer;

    /**
     * Initialize the instance with the passed randomizer.
     *
     * @param \AppserverIo\Apps\Example\Services\RandomizerInterface $randomizer The randomizer instance
     */
    public function __construct(RandomizerInterface $randomizer)
    {
        $this->randomizer = $randomizer;
    }

    /**
     * Retur's a random string.
     *
     * @param string $string The string to randomize
     *
     * @return string The random string
     */
    public function randomizeString($string)
    {
        return $this->randomizer->randomize($string);
    }
}
