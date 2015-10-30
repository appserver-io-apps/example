<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\Cart
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
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use AppserverIo\Apps\Example\Entities\AbstractEntity;

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
class Cart extends AbstractEntity
{

    /**
     * The unique ID of this entity.
     *
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * The session ID the cart is bound to.
     *
     * @var string $sessionId
     * @ORM\Column(name="session_id", type="string", nullable=false)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $sessionId;

    /**
     * The customer ID the cart is bound to.
     *
     * @var integer
     * @ORM\Column(name="customer_id", type="integer", nullable=true)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $customerId = null;

    /**
     * The cart items the cart contain's.
     *
     * @var ArrayCollection<AppserverIo\Apps\Example\Entities\Impl\CartItem>
     * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\Impl\CartItem", mappedBy="cart", cascade={"detach", "persist", "remove"})
     * @JMS\Expose
     * @JMS\Type("ArrayCollection<AppserverIo\Apps\Example\Entities\Impl\CartItem>")
     * @JMS\Accessor(setter="setCartItems")
     */
    protected $cartItems;

    /**
     * Initializes the cart instance.
     */
    public function __construct()
    {
        $this->updateCreatedUpdatedDate();
        $this->cartItems = new ArrayCollection();
    }

    /**
     * Return's the unique cart ID.
     *
     * @return integer The unique cart ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the session ID the cart is bound to.
     *
     * @return string The cart's session ID
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set's the session ID the cart is bound to.
     *
     * @param string $sessionId The cart's session ID
     *
     * @return void
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Return's the Collection with the cart's cart items.
     *
     * @return ArrayCollection<AppserverIo\Apps\Example\Entities\Impl\CartItem> The cart items
     */
    public function getCartItems()
    {
        return $this->cartItems;
    }

    /**
     * Add's the cart items of the passed collection to the cart.
     *
     * @param ArrayCollection<AppserverIo\Apps\Example\Entities\Impl\CartItem> $cartItems The cart items to add
     *
     * @return void
     */
    public function setCartItems($cartItems)
    {

        // set the cart items
        $this->cartItems = $cartItems;

        // set the cart for each cart item
        /** @var \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem */
        foreach ($this->cartItems as $cartItem) {
            $cartItem->setCart($this);
        }
    }

    /**
     * Add's the passed cart item to the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item to add
     *
     * @return void
     */
    public function addCartItem($cartItem)
    {
        $this->cartItems->add($cartItem);
        $cartItem->setCart($this);
    }

    /**
     * Remove's the passed cart item from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item to remove
     *
     * @return void
     */
    public function removeCartItem($cartItem)
    {
        $this->cartItems->removeElement($cartItem);
    }
}
