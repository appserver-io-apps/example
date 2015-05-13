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
	 * @return array
	 * @throws \Exception
	 */
	public function getCartContents();

	/**
	 * @param $sessionId
	 */
	public function initCart($sessionId);

	/**
	 * @return Cart
	 */
	public function getCart();

	/**
	 * @param CartItem $cartItem
	 * @return array
	 * @throws \Exception
	 */
	public function addCartItem($cartItem);

	/**
	 * @param CartItem $cartItem
	 * @return array
	 * @throws \Exception
	 */
	public function updateCartItem($cartItem);
}
