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

/**
 * Abstract processor implementation that provides basic cache functionality.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
abstract class AbstractCacheProcessor extends \Stackable
{

    /**
     * The cache lifetime in seconds.
     *
     * @var integer
     */
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
     * @Resource(type="ApplicationInterface")
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
     * Initializes the session bean with the default cache lifetime.
     *
     * @return void
     * @PostConstruct
     */
    public function initialize()
    {
        $this->CACHE_LIFETIME = 3600;
    }

    /**
     * Returns the cache data for the passed key.
     *
     * @param mixed $key The key of the cache data to return
     *
     * @return null|array The cached data
     */
    public function get($key)
    {

        // create a local copy of the data and the lifetime
        $data = $this->data;
        $cacheLifetime = $this->CACHE_LIFETIME;

        // query whether we've a cached version of the requested data
        if (isset($data[$key])) {
            // query whether the lifetime of the cached data has been expired or not
            if (($data[$key]['created'] + $cacheLifetime) < time()) {
                unset($data[$key]);
                $this->data = $data;
                return null;
            }
            // return the cached data
            return $data[$key]['data'];
        }

        // return null if we've not cached any data
        return null;
    }

    /**
     * Add's the passed data to the cache.
     *
     * @param mixed $key  The cache key
     * @param mixed $data The data to cache
     *
     * @return void
     */
    public function set($key, $data)
    {

        // create a local copy of the data
        $cacheData = $this->data;

        // query whether we've already cached the data
        if (isset($cacheData[$key])) {
            // remove the cached data
            unset($cacheData[$key]);
        }

        // cache the passed data
        $cacheData[$key] = [];
        $cacheData[$key]['created'] = time();
        $cacheData[$key]['data'] = $data;
        $this->data = $cacheData;
    }
}
