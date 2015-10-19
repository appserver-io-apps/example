<?php

/**
 * AppserverIo\Apps\Example\Actions\AbstractEntity
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
namespace AppserverIo\Apps\Example\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract Doctrine entity representation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class AbstractEntity
{

    /**
     * A UNIX timestamp with the entities creation date.
     *
     * @var integer
     * @ORM\Column(name="created_at", type="integer", nullable=false)
     */
    protected $createdAt;

    /**
     * A UNIX timestamp with date the entity has been updated.
     *
     * @var integer
     * @ORM\Column(name="updated_at", type="integer", nullable=false)
     */
    protected $updatedAt;

    /**
     * Mark's the entity as deleted.
     *
     * @var int
     * @ORM\Column(name="deleted", type="integer", nullable=false)
     */
    protected $deleted = 0;

    /**
     * Return's the UNIX timestamp with the entities creation date.
     *
     * @return integer|null The entities creation date
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set's the UNIX timestamp with the entities creation date.
     *
     * @param integer $createdAt The entities creation date
     *
     * @return void
     */
    protected function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Return's the UNIX timestamp with date the entity has been updated.
     *
     * @return integer|null The UNIX timestamp with the entity's last update
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set's the UNIX timestamp with date the entity has been updated.
     *
     * @param integer $updatedAt The UNIX timestamp with the entity's last update
     *
     * @return void
     */
    protected function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Return's the flag if the entity has been deleted or not.
     *
     * @return integer 1 if the entity has been deleted, else 0
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Mark's the entity as deleted, if 1 has been passed.
     *
     * @param integer $deleted 1 if the entity should be marked as deleted, else 0
     *
     * @return void
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Updates the creation and update date with a UNIX timestamp.
     *
     * @return void
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateCreatedUpdatedDate()
    {

        // initialize the update time
        $this->setUpdatedAt(time());

        // query whether or not we've to initialize the creation date too
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(time());
        }
    }
}
