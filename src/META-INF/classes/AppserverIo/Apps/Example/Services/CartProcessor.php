<?php

/**
 * AppserverIo\Apps\Example\Services\CartProcessor
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
namespace AppserverIo\Apps\Example\Services;

use AppserverIo\Apps\Example\Entities\Impl\Cart;

/**
 * A stateful session bean implementation providing shopping cart functionality
 * handled by using Doctrine ORM.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateful
 */
class CartProcessor extends AbstractPersistenceProcessor implements CartProcessorInterface
{

    /**
     * The cart instance.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Cart
     */
    protected $cart;

    /**
     * Returns the cart instance.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Cart The cart instance
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @PostConstruct
     */
    public function postConstruct()
    {

        // create a new cart if not available
        if ($this->cart == null) {
            $this->cart = new Cart();
        }
    }

    /**
     * Returns a Collection or an array with the cart items.
     *
     * @return \Doctrine\Common\Collections\Collection|array The cart items
     */
    public function getCartContents()
    {

        // initialize the array with the cart items
        $items = array();

        // load the cart items if we've a cart
        if ($this->cart) {
            $items = $this->cart->getCartItems();
        }

        // return the cart items
        return $items;
    }

    /**
     * Adds the passed cart item to the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item that has to be added
     * @return void
     */
    public function addCartItem($cartItem)
    {

        // try to load an existing cart item
        $existingItem = $this->loadExistingCartItem($cartItem);

        // query whether we found an existing item or not
        if (empty($existingItem) === false) {
            $this->updateExistingCartItem($existingItem, $cartItem);
        } else {
            $this->createNewCartItem($cartItem);
        }
    }

    /**
     * Removes the passed cart item from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item that has to be removed
     *
     * @return void
     */
    public function removeCartItem($cartItem)
    {

        // create a local copy of the cart
        $cart = $this->cart;

        // load and remove the cart item
        $existingItem = $this->loadExistingCartItem($cartItem);
        $cart->removeCartItem($existingItem);

        // set the cart back to the member
        $this->cart = $cart;
    }

    /**
     * Updates the passed cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item that has to be updated
     * @return void
     */
    public function updateCartItem($cartItem)
    {

        // create a local copy of the cart
        $cart = $this->cart;

        // try to load an existing cart item
        $existingItem = $this->loadExistingCartItem($cartItem);

        // query whether we've found the cart item
        if (empty($existingItem) === false) {
            $this->updateExistingCartItem($existingItem, $cartItem);
        } else {
            throw new \Exception('Item does not exist', 404);
        }

        // set the cart back to the member
        $this->cart = $cart;
    }

    /**
     * Creates a new cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item with the data
     *
     * @return void
     * @throws \Exception Is thrown if the product, the cart item is bound to, doesn't exists
     */
    private function createNewCartItem($cartItem)
    {

        // load the product
        /** @var \AppserverIo\Apps\Example\Entities\Impl\Product $product */
        $product = $this->getEntityManager()->find('AppserverIo\Apps\Example\Entities\Impl\Product', $cartItem->getProductId());

        // query whether the product is available or not
        if (empty($product)) {
            throw new \Exception('Product does not exist', 404);
        }

        // update the cart item data
        $cartItem->setPrice($product->getSalesPrice());
        $cartItem->setProduct($product);
        $cartItem->setCart($this->cart);

        // add the cart item to the cart
        $this->cart->addCartItem($cartItem);
    }

    /**
     * Update's an existing cart item with the data of the passed cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $existingItem The existing cart item
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem     The cart item with the data to update
     *
     * @return void
     */
    private function updateExistingCartItem($existingItem, $cartItem)
    {
        // update the quantity of the new cart item
        /** @var \AppserverIo\Apps\Example\Entities\Impl\CartItem $existingItem  */
        $existingItem->setQuantity($existingItem->getQuantity() + $cartItem->getQuantity());
    }

    /**
     * Loads and returns the cart item with the product ID of the passed cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item containing the product ID of the cart item to return
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\CartItem|null The requested cart item or null
     */
    private function loadExistingCartItem($cartItem)
    {
        // compare the cart item product ID's to find an existing one
        /** @var \AppserverIo\Apps\Example\Entities\Impl\CartItem $existingItem  */
        foreach ($this->cart->getCartItems() as $storedCartItem) {
            if ($storedCartItem->getProductId() == $cartItem->getProductId()) {
                return $storedCartItem;
            }
        }
    }
}
