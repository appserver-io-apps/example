<?php

/**
 * AppserverIo\Apps\Example\Utils\ViewHelper
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

namespace AppserverIo\Apps\Example\Utils;

use AppserverIo\Apps\Example\Entities\Sample;
use AppserverIo\Apps\Example\Entities\Product;
use AppserverIo\Apps\Example\Entities\CartItem;
use AppserverIo\Apps\Example\Exceptions\LoginException;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;

/**
 * Context keys that are used to store data in a application context.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class ViewHelper
{

    /**
     * The applications base URL.
     *
     * @var string
     */
    const BASE_URL = '/';

    /**
     * The singleton view helper instance.
     *
     * @var \AppserverIo\Apps\Example\Utils\ViewHelper
     */
    private static $instance;

    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    /**
     * Singleton implementation to create a new instance.
     *
     * @return \AppserverIo\Apps\Example\Utils\ViewHelper The singleton instance
     */
    public static function singleton()
    {
        if (ViewHelper::$instance == null) {
            ViewHelper::$instance = new ViewHelper();
        }
        return ViewHelper::$instance;
    }

    /**
     * Returns base URL for the html base tag.
     *
     * @return string The base URL
     */
    public function getBaseUrl(HttpServletRequestInterface $servletRequest)
    {
        return $servletRequest->getBaseModifier() . ViewHelper::BASE_URL;
    }

    /**
     * Creates and returns the URL to open the dialog to edit the passed entity.
     *
     * @param \AppserverIo\Apps\Example\Entities\Sample $entity The entity to create the edit link for
     *
     * @return string The URL to open the edit dialog
     */
    public function getEditLink(Sample $entity)
    {
        return sprintf('index.do/index/load?%s=%d', RequestKeys::SAMPLE_ID, $entity->getSampleId());
    }

    /**
     * Creates and returns the URL that has to be invoked to delete the passed entity.
     *
     * @param \AppserverIo\Apps\Example\Entities\Sample $entity The entity to create the deletion link for
     *
     * @return string The URL with the deletion link
     */
    public function getDeleteLink(Sample $entity)
    {
        return sprintf('index.do/index/delete?%s=%d', RequestKeys::SAMPLE_ID, $entity->getSampleId());
    }

    /**
     * Returns TRUE if the web socket server is available, else FALSE.
     *
     * @return boolean TRUE if the web socket server is available
     */
    public function isWebSocketEnabled()
    {
        return is_resource(@fsockopen('127.0.0.1', 8589));
    }

    /**
     * Returns the link to logout the actual user.
     *
     * @return string The link to logout the user actually logged in
     */
    public function getLogoutLink()
    {
        return 'index.do/logout';
    }

    /**
     * Returns the link to edit the data of the actual user.
     *
     * @return string The link to edit the data of the user actually logged in
     */
    public function getUserEditLink()
    {
        return 'index.do/user/index';
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

    /**
     * Creates and returns the URL that has to be invoked to delete the passed entity from the cart.
     *
     * @param \AppserverIo\Apps\Example\Entities\CartItem $entity The entity to create the deletion link for
     *
     * @return string The URL with the deletion link
     */
    public function getDeleteCartItemLink(CartItem $entity)
    {
        return sprintf('index.do/cart/delete?%s=%d', RequestKeys::PRODUCT_ID, $entity->getProductId());
    }

    /**
     * Returns the session with the passed session name.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface $servletRequest  The request instance
     * @param boolean                                                   $create TRUE if a session has to be created if we can't find any
     *
     * @return \AppserverIo\Psr\Servlet\Http\HttpSessionInterface|null The requested session instance
     */
    public function getLoginSession(HttpServletRequestInterface $servletRequest, $create = false)
    {
        return $servletRequest->getSession($create);
    }

    /**
     * Returns TRUE if a user has been logged in, else FALSE.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface $servletRequest  The request instance
     *
     * @return boolean TRUE if a user has been logged into the sytem
     */
    public function isLoggedIn(HttpServletRequestInterface $servletRequest)
    {

        // try to load the session
        $session = $this->getLoginSession($servletRequest);

        // if we can't find a session, something went wrong
        if ($session == null) {
            return false;
        }

        // if we can't find a username, also something went wrong
        if ($session->hasKey(SessionKeys::USERNAME) === false) {
            return false;
        }

        // return the name of the registered user
        return true;
    }

    /**
     * Returns the name of the user currently logged into the system.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface $servletRequest  The request instance
     *
     * @return string Name of the user logged into the system
     * @throws \AppserverIo\Apps\Example\Exceptions\LoginException Is thrown if we can't find a session or a user logged in
     */
    public function getUsername(HttpServletRequestInterface $servletRequest)
    {

        // try to load the session
        $session = $this->getLoginSession($servletRequest);

        // if we can't find a session, something went wrong
        if ($session == null) {
            throw new LoginException(sprintf('Can\'t find session %s', $servletRequest->getRequestedSessionName()));
        }

        // if we can't find a username, also something went wrong
        if ($session->hasKey(SessionKeys::USERNAME) === false) {
            throw new LoginException('Session has no user registered');
        }

        // return the name of the registered user
        return $session->getData(SessionKeys::USERNAME);
    }

    /**
     * Creates and returns the URL to start the .csv import action.
     *
     * @param string $importFile The file info of the .csv file to import
     *
     * @return string The URL to start the file import
     */
    public function getImportLink($importFile)
    {
        return sprintf('index.do/import/import?%s=%s', RequestKeys::FILENAME, $importFile);
    }

    /**
     * Creates and returns the URL to delete the uploaded .csv file.
     *
     * @param string $importFile The file info of the .csv file to delete
     *
     * @return string The URL with the deletion link
     */
    public function getDeleteImportFileLink($importFile)
    {
        return sprintf('index.do/import/delete?%s=%s', RequestKeys::FILENAME, $importFile);
    }
}
