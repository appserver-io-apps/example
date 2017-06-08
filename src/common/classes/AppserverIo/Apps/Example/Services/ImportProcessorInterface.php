<?php

/**
 * AppserverIo\Apps\Example\Services\ImportProcessorInterface
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

use AppserverIo\Lang\Boolean;
use AppserverIo\Psr\HttpMessage\PartInterface;

/**
 * Interface for an import processor.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
interface ImportProcessorInterface
{

    /**
     * Returns an ArrayObject with the CSV files that can be imported.
     *
     * @return \ArrayObject An array with the name of CSV files that can be imported
     */
    public function findAll();

    /**
     * Uploads the passed file part to the temporary upload directory.
     *
     * @param \AppserverIo\Psr\HttpMessage\PartInterface $fileToUpload   The file part to upload
     * @param \AppserverIo\Lang\Boolean                  $watchDirectory TRUE if the directory has to be watched
     *
     * @return void
     */
    public function upload(PartInterface $fileToUpload, Boolean $watchDirectory);

    /**
     * Delete the file from the temporary upload directory
     *
     * @param string $filename The name of the file to upload
     *
     * @return void
     */
    public function delete($filename);

    /**
     * Import the file with the passed filename from the temporary upload directory.
     *
     * @param string $filename The name of the file to import
     *
     * @return void
     */
    public function import($filename);
}
