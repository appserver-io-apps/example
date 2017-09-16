<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\Resource
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

namespace AppserverIo\Apps\Example\Entities\Impl;

use Doctrine\ORM\Mapping as ORM;

/**
 * Doctrine entity that represents a resource.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="resource")
 */
class Resource
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $resourceId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $resourceLocale;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $key;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $message;

    /**
     * Returns the value of the class member resourceId.
     *
     * @return integer Holds the value of the class member resourceId
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Sets the value for the class member resourceId.
     *
     * @param integer $resourceId Holds the value for the class member resourceId
     *
     * @return void
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * Returns the value of the class member resourceLocale.
     *
     * @return string Holds the value of the class member resourceLocale
     */
    public function getResourceLocale()
    {
        return $this->resourceLocale;
    }

    /**
     * Sets the value for the class member resourceLocale.
     *
     * @param string $resourceLocale Holds the value for the class member resourceLocale
     *
     * @return void
     */
    public function setResourceLocale($resourceLocale)
    {
        $this->resourceLocale = $resourceLocale;
    }

    /**
     * Returns the value of the class member key.
     *
     * @return string Holds the value of the class member key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the value for the class member key.
     *
     * @param string $key Holds the value for the class member key
     *
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Returns the value of the class member message.
     *
     * @return string Holds the value of the class member message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the value for the class member message.
     *
     * @param string $message Holds the value for the class member message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
