<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\CartItem
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
use AppserverIo\Apps\Example\Entities\AbstractEntity;

/**
 * Doctrine entity that represents a cart item.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="cart_item")
 * @JMS\ExclusionPolicy("all")
 */
class CartItem extends AbstractEntity
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
     * The ID of the cart the item is bound to.
     *
     * @var integer
     * @ORM\Column(name="cart_id", type="integer", nullable=false)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $cartId;

    /**
     * The product ID the cart item is bound to.
     *
     * @var integer
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $productId;

    /**
     * The price of the cart item.
     *
     * @var integer
     * @ORM\Column(name="price", type="integer", nullable=false)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $price;

    /**
     * The number of items added to the cart.
     *
     * @var integer
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $quantity;

    /**
     * The cart this cart item is bound to.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Cart
     * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Cart", inversedBy="cartItems")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     * @JMS\Expose
     * @JMS\Type("AppserverIo\Apps\Example\Entities\Impl\Cart")
     */
    protected $cart;

    /**
     * The product this cart item is bound to.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Product
     * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @JMS\Expose
     * @JMS\Type("AppserverIo\Apps\Example\Entities\Impl\Product")
     */
    protected $product;

    /**
     * Initializes the cart item instance.
     */
    public function __construct()
    {
        $this->updateCreatedUpdatedDate();
    }

    /**
     * Return's the unique cart item ID.
     *
     * @return integer The unique cart item ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set's the unique cart item ID.
     *
     * @param integer $id The unique cart item ID
     *
     * @return void
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Return's the ID of the cart the item is bound to.
     *
     * @return integer The cart ID the item is bound to
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * Set's the ID of the cart the item is bound to.
     *
     * @param integer $cartId The cart ID the item is bound to
     *
     * @return void
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;
    }

    /**
     * Return's the product ID the cart item is bound to.
     *
     * @return integer The product ID the cart item is bound to
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set's the product ID the cart item is bound to.
     *
     * @param integer $productId The product ID the cart item is bound to
     *
     * @return void
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Return's the price of the cart item.
     *
     * @return integer The cart item's price.
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set's the price of the cart item.
     *
     * @param integer $price The cart item's price
     *
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Return's the number of items added to the cart.
     *
     * @return integer The number of items added to the cart
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set's the number of items added to the cart.
     *
     * @param integer $quantity The number of items added to the cart
     *
     * @return void
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Return's the cart this cart item is bound to.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Cart The cart the cart item is bound to
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set's the cart this cart item is bound to.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Cart $cart The cart the cart item is bound to
     *
     * @return void
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * Return's the product this cart item is bound to.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Product The product the cart item is bound to
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set's the product this cart item is bound to.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Product $product The product the cart item is bound to
     *
     * @return void
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }
}
