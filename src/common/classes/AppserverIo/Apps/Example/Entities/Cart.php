<?php

/**
 * AppserverIo\Apps\Example\Actions\Cart
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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Doctrine entity that represents a cart.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart extends AbstractEntity {

	/**
	 * @var int $id
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $id;

	/**
	 * @var string $sessionId
	 * @ORM\Column(name="session_id", type="string", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("string")
	 */
	protected $sessionId;

	/**
	 * @var int $customerId
	 * @ORM\Column(name="customer_id", type="integer", nullable=true)
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $customerId = null;

	/**
	 * @var string $partnerId
	 * @ORM\Column(name="partner_id", type="string", nullable=true, unique=true)
	 * @JMS\Expose
	 * @JMS\Type("string")
	 */
	protected $partnerId = null;

	/**
	 * INVERSE SIDE
	 * @var ArrayCollection<AppserverIo\Apps\Example\Entities\CartItem>
	 * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\CartItem", mappedBy="cart", cascade={"detach", "persist", "remove"})
	 * @JMS\Expose
	 * @JMS\Type("ArrayCollection<AppserverIo\Apps\Example\Entities\CartItem>")
	 * @JMS\Accessor(setter="setCartItems")
	 */
	protected $cartItems;

    public function __construct()
    {
        $this->updateCreatedUpdatedDate();
		$this->cartItems = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSessionId() {
		return $this->sessionId;
	}

	/**
	 * @param string $sessionId
	 */
	public function setSessionId($sessionId) {
	    $this->sessionId = $sessionId;
	}


	/**
	 * @return string
	 */
	public function getPartnerId() {
		return $this->partnerId;
	}

	/**
	 * @param string $partnerId
	 */
	public function setPartnerId($partnerId) {
		$this->partnerId = $partnerId;
	}

	/**
	 * @return ArrayCollection<AppserverIo\Apps\Example\Entities\CartItem>
	 */
	public function getCartItems() {
		return $this->cartItems;
	}

	/**
	 * @param ArrayCollection<AppserverIo\Apps\Example\Entities\CartItem> $cartItems
	 */
	public function setCartItems($cartItems) {
		$this->cartItems = $cartItems;
		/** @var \AppserverIo\Apps\Example\Entities\CartItem $cartItem */
		foreach ($this->cartItems as $cartItem) {
			$cartItem->setCart($this);
		}
	}

	/**
	 * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem
	 */
	public function addCartItem($cartItem) {
		$this->cartItems->add($cartItem);
		$cartItem->setCart($this);
	}

	/**
	 * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem
	 */
	public function removeCartItem($cartItem) {
		$this->cartItems->removeElement($cartItem);
	}
}