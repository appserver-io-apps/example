<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\Rule
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
 * Doctrine entity that represents a rule.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="rule")
 */
class Rule
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $ruleId;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $roleIdFk;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $assertionIdFk;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $resource;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $privileges;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $permission;

    /**
     * Returns the value of the class member ruleId.
     *
     * @return integer Holds the value of the class member ruleId
     */
    public function getRuleId()
    {
        return $this->ruleId;
    }

    /**
     * Sets the value for the class member ruleId.
     *
     * @param integer $ruleId Holds the value for the class member ruleId
     *
     * @return void
     */
    public function setRuleId($ruleId)
    {
        $this->ruleId = $ruleId;
    }

    /**
     * Returns the value of the class member roleIdFk.
     *
     * @return integer Holds the value of the class member roleIdFk
     */
    public function getRoleIdFk()
    {
        return $this->roleIdFk;
    }

    /**
     * Sets the value for the class member roleIdFk.
     *
     * @param integer $roleIdFk Holds the value for the class member roleIdFk
     *
     * @return void
     */
    public function setRoleIdFk($roleIdFk)
    {
        $this->roleIdFk = $roleIdFk;
    }

    /**
     * Returns the value of the class member assertionIdFk.
     *
     * @return integer Holds the value of the class member assertionIdFk
     */
    public function getAssertionIdFk()
    {
        return $this->assertionIdFk;
    }

    /**
     * Sets the value for the class member assertionIdFk.
     *
     * @param integer $assertionIdFk Holds the value for the class member assertionIdFk
     *
     * @return void
     */
    public function setAssertionIdFk($assertionIdFk = null)
    {
        $this->assertionIdFk = $assertionIdFk;
    }

    /**
     * Returns the value of the class member resource.
     *
     * @return string Holds the value of the class member resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets the value for the class member resource.
     *
     * @param string $resource Holds the value for the class member resource
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Returns the value of the class member privileges.
     *
     * @return string Holds the value of the class member privileges
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * Sets the value for the class member privileges.
     *
     * @param string $privileges Holds the value for the class member privileges
     *
     * @return void
     */
    public function setPrivileges($privileges = null)
    {
        $this->privileges = $privileges;
    }

    /**
     * Returns the value of the class member permission.
     *
     * @return string Holds the value of the class member permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Sets the value for the class member permission.
     *
     * @param string $permission Holds the value for the class member permission
     *
     * @return void
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }
}
