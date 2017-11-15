<?php

/**
 * AppserverIo\Apps\Example\Services\CatalogProcessor
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
 * A SLSB implementation providing shop catalog functionality.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateless
 */
class CatalogProcessor extends AbstractPersistenceProcessor
{

    /**
     * The catalog assembler instance.
     *
     * @var \AppserverIo\Apps\Example\Assembler\CatalogAssembler
     * @Inject(type="\AppserverIo\Apps\Example\Assembler\CatalogAssembler")
     */
    protected $catalogAssembler;

    /**
     * Return's the catalog assembler instance.
     *
     * @return \AppserverIo\Apps\Example\Assembler\CatalogAssembler
     */
    protected function getCatalogAssembler()
    {
        return $this->catalogAssembler;
    }

    /**
     * Return's the catalog view data for the passed category.
     *
     * @param string  $slug   The category slug to return the catalog view data for
     * @param integer $limit  The maxium number of rows to return
     * @param integer $offset The row to start with
     *
     * @return \AppserverIo\Apps\Example\Dtos\CatalogViewData The DTO with the catalog view data
     */
    public function getCatalogViewData($slug, $limit = 100, $offset = 0)
    {
        return $this->getCatalogAssembler()->getCatalogViewData($slug, $limit, $offset);
    }
}
