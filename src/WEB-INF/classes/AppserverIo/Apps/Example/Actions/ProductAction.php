<?php

/**
 * AppserverIo\Apps\Example\Actions\ProductAction
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
use AppserverIo\Apps\Example\Entities\Product;
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
class ProductAction extends ExampleBaseAction
{

    /**
     * The relative path, up from the webapp path, to the template to use.
     *
     * @var string
     */
    const PRODUCT_TEMPLATE = 'static/templates/product.phtml';

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
        $overviewData = $this->getProxy(ProxyKeys::PRODUCT_PROCESSOR)->findAll();
        $this->setAttribute(ContextKeys::OVERVIEW_DATA, $overviewData);
        $servletResponse->appendBodyStream($this->processTemplate(ProductAction::PRODUCT_TEMPLATE, $servletRequest, $servletResponse));
    }

    /**
     * Creates and returns the URL that has to be invoked to add the passed entity to the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\Product $entity The entity to create the add to cart link for
     *
     * @return string The URL with the add to cart link
     */
    public function getAddToCartLink(Product $entity)
    {
        return sprintf('index.do/cart/addToCart?%s=%d', RequestKeys::PRODUCT_ID, $entity->getId());
    }
}
