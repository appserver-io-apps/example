<?php

/**
 * AppserverIo\Apps\Example\Utils\RequestKeys
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

/**
 * Request keys that are used to store data in a request context.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class RequestKeys
{

    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    /**
     * The key for a collection with error messages.
     *
     * @return string
     */
    const ERROR_MESSAGES = 'error.messages';

    /**
     * The key for a collection with entities.
     *
     * @return string
     */
    const OVERVIEW_DATA = 'overview.data';

    /**
     * The key for an entity.
     *
     * @return string
     */
    const VIEW_DATA = 'view.data';

    /**
     * The key for a 'action'.
     *
     * @return string
     */
    const ACTION = 'action';

    /**
     * The key for a 'sampleId'.
     *
     * @return string
     */
    const SAMPLE_ID = 'sampleId';

    /**
     * The key for a 'productId'.
     *
     * @return string
     */
    const PRODUCT_ID = 'productId';

    /**
     * The key for a 'cartItemId'.
     *
     * @return string
     */
    const CART_ITEM_ID = 'cartItemId';

    /**
     * The key for a 'name'.
     *
     * @return string
     */
    const NAME = 'name';

    /**
     * The key for a 'username'.
     *
     * @return string
     */
    const USERNAME = 'p_username';

    /**
     * The key for a 'password'.
     *
     * @return string
     */
    const PASSWORD = 'p_password';

    /**
     * The key for a 'filename'.
     *
     * @return string
     */
    const FILENAME = 'filename';

    /**
     * The key for a 'fileToUpload'.
     *
     * @return string
     */
    const FILE_TO_UPLOAD = 'fileToUpload';

    /**
     * The key for a 'userId'.
     *
     * @return string
     */
    const USER_ID = 'userId';

    /**
     * The key for a 'ldapSynced'.
     *
     * @return string
     */
    const LDAP_SYNCED = 'ldapSynced';

    /**
     * The key for a 'enabled'.
     *
     * @return string
     */
    const ENABLED = 'enabled';

    /**
     * The key for a 'syncedAt'.
     *
     * @return string
     */
    const SYNCED_AT = 'syncedAt';

    /**
     * The key for a 'contractedHours'.
     *
     * @return string
     */
    const CONTRACTED_HOURS = 'contractedHours';

    /**
     * The key for a 'rate'.
     *
     * @return string
     */
    const RATE = 'rate';

    /**
     * The key for a 'email'.
     *
     * @return string
     */
    const EMAIL = 'email';

    /**
     * The key for a 'userLocale'.
     *
     * @return string
     */
    const USER_LOCALE = 'userLocale';

    /**
     * The key for a 'watchDirectory'.
     *
     * @return string
     */
    const WATCH_DIRECTORY = 'watchDirectory';

    /**
     * The key for the catalog view data.
     *
     * @var string
     */
    const CATALOG_VIEW_DATA = 'catalog.view.data';

    /**
     * The key for the category slug.
     *
     * @var string
     */
    const SLUG = 'slug';
}
