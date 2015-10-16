<?php

/**
 * AppserverIo\Apps\Example\Actions\CartItem
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
use JMS\Serializer\Annotation as JMS;

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
	 * @var int $id
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $id;

	/**
	 * @var int $cartId
	 * @ORM\Column(name="cart_id", type="integer", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $cartId;

	/**
	 * @var float $price
	 * @ORM\Column(name="price", type="float", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("double")
	 */
	protected $price;

	/**
	 * @var int $quantity
	 * @ORM\Column(name="quantity", type="integer", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $quantity;

	/**
	 * @var int $productId
	 * @ORM\Column(name="product_id", type="integer", nullable=false)
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $productId;

	/**
	 * OWNING SIDE
	 * @var \AppserverIo\Apps\Example\Entities
	 * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Cart", inversedBy="cartItems")
	 * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
	 * @JMS\Expose
	 * @JMS\Type("AppserverIo\Apps\Example\Entities\Cart")
	 */
	protected $cart;

	/**
	 * @var \AppserverIo\Apps\Example\Entities\Product $product
	 * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Product")
	 * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
	 * @JMS\Expose
	 * @JMS\Type("AppserverIo\Apps\Example\Entities\Product")
	 */
	protected $product;

    public function __construct()
    {
        $this->updateCreatedUpdatedDate();
    }

	/**
	 * @return int
	 */
	public function setId($id) {
		return $this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getCartId() {
		return $this->cartId;
	}

	/**
	 * @param int $cartId
	 */
	public function setCartId($cartId) {
		$this->cartId = $cartId;
	}

	/**
	 * @return float
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param float $price
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * @return int
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	 * @return int
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * @param int $productId
	 */
	public function setProductId($productId) {
		$this->productId = $productId;
	}

	/**
	 * @return Cart
	 */
	public function getCart() {
		return $this->cart;
	}

	/**
	 * @param Cart $cart
	 */
	public function setCart($cart) {
		$this->cart = $cart;
	}

	/**
	 * @return \AppserverIo\Apps\Example\Entities\Product
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * @param \AppserverIo\Apps\Example\Entities\Product $product
	 */
	public function setProduct($product) {
		$this->product = $product;
	}
}