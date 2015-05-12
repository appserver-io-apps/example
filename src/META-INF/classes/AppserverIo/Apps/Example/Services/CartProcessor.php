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
     *
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     *
     * @return array
     * @throws BadRequestException
     */
    public function getCartContents()
    {
        $items = array();
        if ($cart = $this->getCart()) {
            $items = $cart->getCartItems();
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

        /**
         *
         * @var \Doctrine\ORM\EntityRepository $cartRepository
         */
        $cartRepository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Cart');
        $storedCart = $cartRepository->findOneBySessionId($sessionId);

        if ($storedCart instanceof Cart) {

            $this->cart = $storedCart;

            error_log("Restore Cart for session-ID $sessionId");

        } else {

            $this->cart = new Cart();
            $this->cart->setSessionId($sessionId);

            $entityManager->persist($this->cart);
            $entityManager->flush();

            error_log("Created new Cart for session-ID $sessionId");
        }
    }

    /**
     *
     * @param CartItem $cartItem
     * @return array
     * @throws BadRequestException
     */
    public function addCartItem($cartItem)
    {
        $existingItem = $this->loadExistingCartItem($cartItem);

        if (! empty($existingItem)) {
            $this->updateExistingCartItem($existingItem, $cartItem);
        } else {
            $this->createNewCartItem($cartItem);
        }

        $this->getEntityManager()->persist($this->cart);
        $this->getEntityManager()->flush();

        return array(
            'cartItems' => $this->cart->getCartItems()
        );
    }

    /**
     *
     * @param CartItem $cartItem
     * @return array
     * @throws BadRequestException
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

        return array(
            'cartItems' => $this->cart->getCartItems()
        );
    }

    /**
     *
     * @param CartItem $cartItem
     * @throws BadRequestException
     */
    private function createNewCartItem($cartItem)
    {

        /**
         *
         * @var Product $product
         */
        $product = $this->entityManager->find('AppserverIo\Apps\Example\Entities\Product', $cartItem->getProductId());
        if (empty($product)) {
            throw new BadRequestException('Product does not exist', 404);
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
        /**
         *
         * @var CartItem $existingItem
         */
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
