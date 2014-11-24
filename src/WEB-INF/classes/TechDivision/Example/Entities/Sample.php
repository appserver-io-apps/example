<?php

/**
 * AppserverIo\Apps\Example\Actions\Sample
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

namespace AppserverIo\Apps\Example\Entities;

/**
 * Doctrine entity that represents a sample.
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
 * @Entity
 * @Table(name="sample")
 */
class Sample
{

    /**
     * @var integer
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $sampleId;

    /**
     * @var string
     *
     * @Column(type="string", length=255)
     */
    public $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="User", mappedBy="sample", cascade={"all"}, fetch="EAGER")
     */
    protected $users;

    /**
     * Initializes the collection with the users.
     *
     * @return void
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Sets the value for the class member sampleId.
     *
     * @param integer $sampleId Holds the value for the class member sampleId
     *
     * @return void
     */
    public function setSampleId($sampleId)
    {
        $this->sampleId = $sampleId;
    }

    /**
     * Returns the value of the class member sampleId.
     *
     * @return integer Holds the value of the class member sampleId
     */
    public function getSampleId()
    {
        return $this->sampleId;
    }

    /**
     * Sets the value for the class member name.
     *
     * @param string $name Holds the value for the class member name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the value of the class member name.
     *
     * @return string Holds the value of the class member name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the user collection.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The user collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
