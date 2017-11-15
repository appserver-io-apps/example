<?php

/**
 * AppserverIo\Apps\Example\Actions\CartAction
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

namespace AppserverIo\Apps\Example\Actions;

use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Apps\Example\Utils\ViewHelper;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Apps\Example\Entities\Impl\CartItem;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example action implementation that loads data over a persistence container proxy
 * and renders a list, based on the returned values.
 *
 * Additional it provides functionality to edit, delete und persist the data after
 * changing it.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Path(name="/cart")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/cart.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/cart.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 *
 */
class CartAction extends DispatchAction
{

    /**
     * The CartProcessor instance to handle the shopping cart functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\CartProcessor
     * @EnterpriseBean
     */
    protected $cartProcessor;

    /**
     * Returns the CartProcessor instance to handle the shopping cart functionality.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance
     */
    public function getCartProcessor()
    {
        return $this->cartProcessor;
    }

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Loads all sample data and attaches it to the servlet context ready to be rendered
     * by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/index")
     */
    public function indexAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // append the shopping cart data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getCartProcessor()->getCartContents());
    }

    /**
     * Adds the product entity with the product ID found in the request to the cart and
     * redirects to the cart.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @throws \Exception
     *
     * @Action(name="/addToCart/:productId", restrictions={{"productId", "\d+"}})
     */
    public function addToCartAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $productId = $servletRequest->getParameter(RequestKeys::PRODUCT_ID, FILTER_VALIDATE_INT);
        if ($productId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::PRODUCT_ID));
        }

        // start the session
        ViewHelper::singleton()->getLoginSession($servletRequest, true)->start();

        // create a new cart item from the passed product-ID
        $cartItem = new CartItem();
        $cartItem->setQuantity(1);
        $cartItem->setProductId($productId);

        // delete the entity
        $this->getCartProcessor()->addCartItem($cartItem);

        // append the shopping cart data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getCartProcessor()->getCartContents());
    }

    /**
     * Deletes the cart item entity with the cart item ID found in the request from the cart.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/delete/:productId", restrictions={{"productId", "\d+"}})
     */
    public function deleteAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $productId = $servletRequest->getParameter(RequestKeys::PRODUCT_ID, FILTER_VALIDATE_INT);
        if ($productId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::PRODUCT_ID));
        }

        // create a new cart item from the passed product-ID
        $cartItem = new CartItem();
        $cartItem->setProductId($productId);

        // delete the cart item entity
        $this->getCartProcessor()->removeCartItem($cartItem);

        // append the shopping cart data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getCartProcessor()->getCartContents());
    }
}
