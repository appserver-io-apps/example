<?php

/**
 * AppserverIo\Apps\Example\Services\AbstractCacheProcessor
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

use AppserverIo\Psr\Application\ApplicationInterface;

/**
 * Abstract processor implementation that provides basic cache functionality.
 *
 * @author Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link https://github.com/appserver-io-apps/example
 * @link http://www.appserver.io
 */
abstract class AbstractCacheProcessor extends \Stackable
{

    private $CACHE_LIFETIME = 0;

    /**
     * Array containing the cache data.
     *
     * @var array
     */
    private $data = [];

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

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
     * @PostConstruct
     */
    public function initialize()
    {
        $this->CACHE_LIFETIME = 3600;
    }

    /**
     *
     * @param mixed $key
     * @return null|array The cached data
     */
    public function get($key)
    {
        $data = $this->data;
        $cacheLifetime = $this->CACHE_LIFETIME;

        error_log(print_r($this->data, true));

        if (isset($data[$key])) {
            if (($data[$key]['created'] + $cacheLifetime) < time()) {
                unset($data[$key]);
                $this->data = $data;
                return NULL;
            }
            return $data[$key]['data'];
        }
        return NULL;
    }

    /**
     *
     * @param mixed $key
     * @param mixed $data
     */
    public function set($key, $data)
    {
        $cacheData = $this->data;
        if (isset($cacheData[$key])) {
            unset($cacheData[$key]);
        }
        $cacheData[$key] = [];
        $cacheData[$key]['created'] = time();
        $cacheData[$key]['data'] = $data;
        $this->data = $cacheData;
    }
}
