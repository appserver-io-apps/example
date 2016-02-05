<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\User
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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine entity that represents a user.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $userId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $userLocale;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $rate;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $contractedHours;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $ldapSynced;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $syncedAt;

    /**
     * @var \AppserverIo\Apps\Example\Entities\Impl\Sample
     *
     * @ORM\ManyToOne(targetEntity="Sample", inversedBy="users", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="sampleIdFk", referencedColumnName="sampleId")
     */
    protected $sample;

    /**
     * The user's roles.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection<\AppserverIo\Apps\Example\Entities\Impl\Roles> $roles
     * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Role", mappedBy="user", cascade={"persist"})
     */
    protected $roles;

    /**
     * Initialize the user instance.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * Returns the value of the class member userId.
     *
     * @return integer Holds the value of the class member userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the value for the class member userId.
     *
     * @param integer $userId Holds the value for the class member userId
     *
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Returns the value of the class member userId.
     *
     * @return integer Holds the value of the class member userId
     */
    public function getSampleIdFk()
    {
        return $this->sampleIdFk;
    }

    /**
     * Sets the value for the class member sampleIdFk.
     *
     * @param integer $sampleIdFk Holds the value for the class member sampleIdFk
     *
     * @return void
     */
    public function setSampleIdFk($sampleIdFk)
    {
        $this->sampleIdFk = $sampleIdFk;
    }

    /**
     * Returns the value of the class member email.
     *
     * @return string Holds the value of the class member email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the value for the class member email.
     *
     * @param string $email Holds the value for the class member email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the value of the class member username.
     *
     * @return string Holds the value of the class member username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the value for the class member username.
     *
     * @param string $username Holds the value for the class member username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Returns the value of the class member userLocale.
     *
     * @return string Holds the value of the class member userLocale
     */
    public function getUserLocale()
    {
        return $this->userLocale;
    }

    /**
     * Sets the value for the class member userLocale.
     *
     * @param string $userLocale Holds the value for the class member userLocale
     *
     * @return void
     */
    public function setUserLocale($userLocale)
    {
        $this->userLocale = $userLocale;
    }

    /**
     * Returns the value of the class member password.
     *
     * @return string Holds the value of the class member password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the value for the class member password.
     *
     * @param string $password Holds the value for the class member password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the value of the class member enabled.
     *
     * @return boolean Holds the value of the class member enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Sets the value for the class member enabled.
     *
     * @param boolean $enabled Holds the value for the class member enabled
     *
     * @return void
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Returns the value of the class member rate.
     *
     * @return integer Holds the value of the class member rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets the value for the class member rate.
     *
     * @param integer $rate Holds the value for the class member rate
     *
     * @return void
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * Returns the value of the class member contractedHours.
     *
     * @return integer Holds the value of the class member contractedHours
     */
    public function getContractedHours()
    {
        return $this->contractedHours;
    }

    /**
     * Sets the value for the class member contractedHours.
     *
     * @param integer $contractedHours Holds the value for the class member contractedHours
     *
     * @return void
     */
    public function setContractedHours($contractedHours)
    {
        $this->contractedHours = $contractedHours;
    }

    /**
     * Returns the value of the class member ldapSynced.
     *
     * @return boolean Holds the value of the class member ldapSynced
     */
    public function getLdapSynced()
    {
        return $this->ldapSynced;
    }

    /**
     * Sets the value for the class member ldapSynced.
     *
     * @param boolean $ldapSynced Holds the value for the class member ldapSynced
     *
     * @return void
     */
    public function setLdapSynced($ldapSynced)
    {
        $this->ldapSynced = $ldapSynced;
    }

    /**
     * Returns the value of the class member syncedAt.
     *
     * @return integer Holds the value of the class member syncedAt
     */
    public function getSyncedAt()
    {
        return $this->syncedAt;
    }

    /**
     * Sets the value for the class member syncedAt.
     *
     * @param integer $syncedAt Holds the value for the class member syncedAt
     *
     * @return void
     */
    public function setSyncedAt($syncedAt = null)
    {
        $this->syncedAt = $syncedAt;
    }

    /**
     * Set's the user's roles.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $roles The user's roles
     *
     * @return void
     */
    public function setRoles(ArrayCollection $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Return's the user's roles.
     *
     * @return Doctrine\Common\Collections\ArrayCollection The roles
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
