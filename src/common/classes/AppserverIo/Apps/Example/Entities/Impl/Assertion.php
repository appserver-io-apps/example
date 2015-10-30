<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\Assertion
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
 * Doctrine entity that represents a assertion.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="assertion")
 */
class Assertion
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $assertionId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $includeFile;

    /**
     * Returns the value of the class member assertionId.
     *
     * @return integer Holds the value of the class member assertionId
     */
    public function getAssertionId()
    {
        return $this->assertionId;
    }

    /**
     * Sets the value for the class member assertionId.
     *
     * @param integer $assertionId Holds the value for the class member assertionId
     *
     * @return void
     */
    public function setAssertionId($assertionId)
    {
        $this->assertionId = $assertionId;
    }

    /**
     * Returns the value of the class member type.
     *
     * @return string Holds the value of the class member type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value for the class member type.
     *
     * @param string $type Holds the value for the class member type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the value of the class member includeFile.
     *
     * @return string Holds the value of the class member includeFile
     */
    public function getIncludeFile()
    {
        return $this->includeFile;
    }

    /**
     * Sets the value for the class member includeFile.
     *
     * @param string $includeFile Holds the value for the class member includeFile
     *
     * @return void
     */
    public function setIncludeFile($includeFile)
    {
        $this->includeFile = $includeFile;
    }
}
