<?php

/**
 * AppserverIo\Apps\Example\Services\CartProcessorInterface
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

/**
 * Interface for a cart processor.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
interface CartProcessorInterface
{

    /**
     * Returns a Collection or an array with the cart items.
     *
     * @return \Doctrine\Common\Collections\Collection|array The cart items
     */
    public function getCartContents();

    /**
     * Returns the cart instance.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Cart The cart instance
     */
    public function getCart();

    /**
     * Adds the passed cart item to the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item that has to be added
     * @return void
     */
    public function addCartItem($cartItem);

    /**
     * Updates the passed cart item.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item that has to be updated
     * @return void
     */
    public function updateCartItem($cartItem);

    /**
     * Removes the passed cart item from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\CartItem $cartItem The cart item that has to be removed
     * @return void
     */
    public function removeCartItem($cartItem);
}
