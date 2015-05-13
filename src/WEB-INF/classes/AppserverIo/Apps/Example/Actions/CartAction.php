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

use AppserverIo\Apps\Example\Utils\ProxyKeys;
use AppserverIo\Apps\Example\Utils\ContextKeys;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Apps\Example\Entities\CartItem;
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
 */
class CartAction extends ExampleBaseAction
{

    /**
     * The relative path, up from the webapp path, to the template to use.
     *
     * @var string
     */
    const CART_TEMPLATE = 'static/templates/cart.phtml';

    /**
     * We always start the session, because we need a session-ID for our SFSB.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @see \AppserverIo\Routlt\BaseAction::preDispatch()
     */
    public function preDispatch(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // set servlet request/response
        $this->setServletRequest($servletRequest);
        $this->setServletResponse($servletResponse);

        // start the session
        $this->getLoginSession(true)->start();

        // initialize the cart for this session-ID
        $this->getProxy(ProxyKeys::CART_PROCESSOR)->initCart($this->getLoginSession()->getId());
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
     * @return void
     */
    public function indexAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        $overviewData = $this->getProxy(ProxyKeys::CART_PROCESSOR)->getCartContents();
        $this->setAttribute(ContextKeys::OVERVIEW_DATA, $overviewData);
        $servletResponse->appendBodyStream($this->processTemplate(CartAction::CART_TEMPLATE, $servletRequest, $servletResponse));
    }

    /**
     * Adds the product entity with the product ID found in the request to the cart and
     * redirects to the cart.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     *
     * @throws \Exception
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function addToCartAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $productId = $servletRequest->getParameter(RequestKeys::PRODUCT_ID, FILTER_VALIDATE_INT);
        if ($productId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::PRODUCT_ID));
        }

        // create a new cart item from the passed product-ID
        $cartItem = new CartItem();
        $cartItem->setQuantity(1);
        $cartItem->setProductId($productId);

        // delete the entity
        $this->getProxy(ProxyKeys::CART_PROCESSOR)->addCartItem($cartItem);

        // reload the cart data
        $this->indexAction($servletRequest, $servletResponse);
    }

    /**
     * Deletes the cart item entity with the cart item ID found in the request from the cart.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     *
     * @throws \Exception
     */
    public function deleteAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $cartItemId = $servletRequest->getParameter(RequestKeys::CART_ITEM_ID, FILTER_VALIDATE_INT);
        if ($cartItemId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::CART_ITEM_ID));
        }

        // delete the entity
        $this->getProxy(ProxyKeys::CART_PROCESSOR)->removeCartItemByCartItemId($cartItemId);

        // reload all entities and render the dialog
        $this->indexAction($servletRequest, $servletResponse);
    }

    /**
     * Creates and returns the URL that has to be invoked to delete the passed entity from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $entity The entity to create the deletion link for
     *
     * @return string The URL with the deletion link
     */
    public function getDeleteCartItemLink(CartItem $entity)
    {
        return sprintf('index.do/cart/delete?%s=%d', RequestKeys::CART_ITEM_ID, $entity->getId());
    }
}
