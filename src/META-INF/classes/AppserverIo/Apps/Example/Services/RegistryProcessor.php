<?php

/**
 * AppserverIo\Apps\Example\Services\RegistryProcessor
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

use TechDivision\Import\Services\RegistryProcessorInterface;

/**
 * Array based implementation of a registry.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 *
 * @Singleton
 * @Startup
 */
class RegistryProcessor extends \Stackable implements RegistryProcessorInterface
{

    /**
     * A storage for the attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Initialize the registry processor.
     *
     * @return void
     * @PostConstruct
     */
    public  function postConstruct()
    {
        $this->attributes = new \Stackable();
    }

    /**
     * Register the passed attribute under the specified key in the registry.
     *
     * @param mixed $key   The key to register the value with
     * @param mixed $value The value to be registered
     *
     * @return void
     * @throws \Exception Is thrown, if the key has already been used
     */
    public function setAttribute($key, $value)
    {

        // query whether or not the key has already been used
        if (isset($this->attributes[$key])) {
            throw new \Exception(sprintf('Try to override data with key %s', $key));
        }

        // set the attribute in the registry
        $this->attributes[$key] = $value;
    }

    /**
     * Return's the attribute with the passed key from the registry.
     *
     * @param mixed $key The key to return the attribute for
     *
     * @return mixed The requested attribute
     */
    public function getAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }

    /**
     * Query whether or not an attribute with the passed key has already been registered.
     *
     * @param mixed $key The key to query for
     *
     * @return boolean TRUE if the attribute has already been registered, else FALSE
     */
    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Remove the attribute with the passed key from the registry.
     *
     * @param mixed $key The key of the attribute to return
     *
     * @return void
     */
    public function removeAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
        }
    }

    /**
     * Raises the value for the attribute with the passed key by one.
     *
     * @param mixed $key         The key of the attribute to raise the value for
     * @param mixed $counterName The name of the counter to raise
     *
     * @return integer The counter's new value
     */
    public function raiseCounter($key, $counterName)
    {

        // raise/initialize the value
        if (isset($this->attributes[$key][$counterName])) {
            $this->attributes[$key][$counterName]++;
        } else {
            $this->attributes[$key][$counterName] = 1;
        }

        // return the new value
        return $this->attributes[$key][$counterName];
    }

    /**
     * This method merges the passed attributes with an array that
     * has already been added under the passed key.
     *
     * If no value will be found under the passed key, the attributes
     * will simply be registered.
     *
     * @param mixed $key        The key of the attributes that has to be merged with the passed ones
     * @param array $attributes The attributes that has to be merged with the exising ones
     *
     * @return void
     * @throws \Exception Is thrown, if the already registered value is no array
     * @link http://php.net/array_replace_recursive
     */
    public function mergeAttributesRecursive($key, array $attributes)
    {

        // if the key not exists, simply add the new attributes
        if (!isset($this->attributes[$key])) {
            $this->attributes[$key] = $attributes;
            return;
        }

        // if the key exists and the value is an array, merge it with the passed array
        if (isset($this->attributes[$key]) && is_array($this->attributes[$key])) {
            $this->attributes[$key] = array_replace_recursive($this->attributes[$key], $attributes);
            return;
        }

        // throw an exception if the key exists, but the found value is not of type array
        throw new \Exception(sprintf('Can\'t merge attributes, because value for key %s already exists, but is not of type array', $key));
    }
}
