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
 * @author Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link https://github.com/appserver-io-apps/example
 * @link http://www.appserver.io
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

            parent::postDetach();

        } catch (\Exception $e) {

        }
    }

    /**
     * Returns a Collection or an array with the cart items.
     *
     * @return \Doctrine\Common\Collections\Collection|array The cart items
     */
    public function getCartContents()
    {

        $items = array();

        if ($this->cart) {
            $items = $this->cart->getCartItems();
        }

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

        // Check if the cart is initialized and contains items => probably this method
        // is called wrong.
        if ($this->cart instanceof Cart && $this->cart->getCartItems()->count() > 0) {
            return;
        }

        // Try to load the session from the DB => AppServer has crashed or restarted
        $entityManager = $this->getEntityManager();

        /** @var \Doctrine\ORM\EntityRepository $cartRepository */
        $cartRepository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Cart');
        $storedCart = $cartRepository->findOneBySessionId($sessionId);

        if ($storedCart instanceof Cart) {

            $this->cart = $storedCart;

        } else {

            $cart = new Cart();
            $cart->setSessionId($sessionId);

            $entityManager->persist($cart);
            $entityManager->flush();

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

        if (!empty($existingItem)) {
            $this->updateExistingCartItem($existingItem, $cartItem);
        } else {
            $this->createNewCartItem($cartItem);
        }

        $this->getEntityManager()->persist($this->cart);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes the passed cart item from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $cartItem The cart item that has to be removed
     * @return void
     */
    public function removeCartItem($cartItem)
    {

        $cart = $this->cart;

        $existingItem = $this->loadExistingCartItem($cartItem);

        $cart->removeCartItem($existingItem);

        error_log("Found " . sizeof($cart->getCartItems()) . " cart items");

        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();

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

        $existingItem = $this->loadExistingCartItem($cartItem);

        if (! empty($existingItem)) {
            $this->updateExistingCartItem($existingItem, $cartItem);
        } else {
            throw new BadRequestException('Item does not exist', 404);
        }

        $this->getEntityManager()->persist($this->cart);
        $this->getEntityManager()->flush();
    }

    /**
     *
     * @param CartItem $cartItem
     * @throws \Exception
     */
    private function createNewCartItem($cartItem)
    {

        /** @var Product $product */
        $product = $this->entityManager->find('AppserverIo\Apps\Example\Entities\Product', $cartItem->getProductId());
        if (empty($product)) {
            throw new \Exception('Product does not exist', 404);
        }

        $cartItem->setPrice($product->getSalesPrice());
        $cartItem->setProduct($product);
        $cartItem->setCart($this->cart);

        $this->cart->addCartItem($cartItem);
    }

    /**
     *
     * @param CartItem $existingItem
     * @param CartItem $cartItem
     */
    private function updateExistingCartItem($existingItem, $cartItem)
    {
        /** @var CartItem $existingItem  */
        $existingItem->setQuantity($existingItem->getQuantity() + $cartItem->getQuantity());
    }

    /**
     *
     * @param CartItem $cartItem
     * @return CartItem
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
