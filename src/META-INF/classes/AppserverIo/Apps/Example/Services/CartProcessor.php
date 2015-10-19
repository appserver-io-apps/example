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

use AppserverIo\Apps\Example\Entities\Cart;
use AppserverIo\Apps\Example\Entities\Product;
use AppserverIo\Apps\Example\Entities\CartItem;

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
class CartProcessor extends AbstractProcessor implements CartProcessorInterface
{

    /**
     * The cart instance.
     *
     * @var \AppserverIo\Apps\Example\Entities\Cart
     */
    protected $cart;

    /**
     * Returns the cart instance.
     *
     * @return \AppserverIo\Apps\Example\Entities\Cart The cart instance
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Dummy implementation for demonstration purposes.
     *
     * @return void
     * @PostDetach
     */
    public function postDetach()
    {
        try {
            // ATTENTION: This is necessary to let Doctrine manage the entity.
            //            When not merged, proxy classes are returned to the
            //            view and no autoloader is aware how to resolve the
            //            class definitions!!
            $this->cart = $this->getEntityManager()->merge($this->cart);

            // call the parent method
            parent::postDetach();

        } catch (\Exception $e) {
            // @TODO Still to implement
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
     * Initializes the cart for the passed session-ID.
     *
     * @param string $sessionId The session-ID to initialize the cart with
     *
     * @return void
     */
    public function initCart($sessionId)
    {

        // ceck if the cart is initialized and contains items => probably this method is called wrong
        if ($this->cart instanceof Cart && $this->cart->getCartItems()->count() > 0) {
            return;
        }

        // try to load the session from the DB => AppServer has crashed or restarted
        $entityManager = $this->getEntityManager();

        /** @var \Doctrine\ORM\EntityRepository $cartRepository */
        $cartRepository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Cart');
        $storedCart = $cartRepository->findOneBySessionId($sessionId);

        if ($storedCart instanceof Cart) {
            $this->cart = $storedCart;
        } else {
            // create a new cart
            $cart = new Cart();
            $cart->setSessionId($sessionId);

            // persist the cart
            $entityManager->persist($cart);
            $entityManager->flush();

            // set the created cart
            $this->cart = $cart;
        }
    }

    /**
     * Adds the passed cart item to the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem The cart item that has to be added
     * @return void
     */
    public function addCartItem($cartItem)
    {

        // create a local copy of the cart
        $cart = $this->cart;

        // try to load an existing cart item
        $existingItem = $this->loadExistingItem($cartItem);

        // query whether we found an existing item or not
        if (empty($existingItem) === false) {
            $this->updateExistingCartItem($existingItem, $cartItem);
        } else {
            $this->createNewCartItem($cartItem);
        }

        // save the cart
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();

        // set the cart back to the member
        $this->cart = $cart;
    }

    /**
     * Removes the passed cart item from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem The cart item that has to be removed
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

        // save the cart
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();

        // set the cart back to the member
        $this->cart = $cart;
    }

    /**
     * Updates the passed cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem The cart item that has to be updated
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
            throw new BadRequestException('Item does not exist', 404);
        }

        // save the cart
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();

        // set the cart back to the member
        $this->cart = $cart;
    }

    /**
     * Creates a new cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem The cart item with the data
     *
     * @return void
     * @throws \Exception Is thrown if the product, the cart item is bound to, doesn't exists
     */
    private function createNewCartItem($cartItem)
    {

        // load the product
        /** @var Product $product */
        $product = $this->entityManager->find('AppserverIo\Apps\Example\Entities\Product', $cartItem->getProductId());

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
     * @param \AppserverIo\Apps\Example\Entities\CartItem $existingItem The existing cart item
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem     The cart item with the data to update
     *
     * @return void
     */
    private function updateExistingCartItem($existingItem, $cartItem)
    {
        /** @var CartItem $existingItem  */
        $existingItem->setQuantity($existingItem->getQuantity() + $cartItem->getQuantity());
    }

    /**
     * Loads and returns the cart item with the product ID of the passed cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem The cart item containing the product ID of the cart item to return
     *
     * @return \AppserverIo\Apps\Example\Entities\CartItem The requested cart item
     */
    private function loadExistingCartItem($cartItem)
    {
        foreach ($this->cart->getCartItems() as $storedCartItem) {
            if ($storedCartItem->getProductId() == $cartItem->getProductId()) {
                return $storedCartItem;
            }
        }
    }
}
