<?php

/**
 * AppserverIo\Apps\Example\Services\ImportProcessor
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
use AppserverIo\Messaging\StringMessage;
use AppserverIo\Psr\HttpMessage\PartInterface;

/**
 * A SLSB that handles the import process.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateless
 */
class ImportProcessor extends AbstractPersistenceProcessor implements ImportProcessorInterface
{

    /**
     * The queue sender for sending the import message.
     *
     * @var AppserverIo\Messaging\QueueSender
     * @Resource(name="import", type="pms/import")
     */
    protected $importSender;

    /**
     * The queue sender for sending the message to create an interval timer.
     *
     * @var AppserverIo\Messaging\QueueSender
     * @Resource(name="createAIntervalTimer", type="pms/createAIntervalTimer")
     */
    protected $createAIntervalTimerSender;

    /**
     * Returns the queue sender for sending the import message.
     *
     * @return @var AppserverIo\Messaging\QueueSender The queue sender
     */
    protected function getImportSender()
    {
        return $this->importSender;
    }

    /**
     * Returns the queue sender for sending the message to create an interval timer.
     *
     * @return @var AppserverIo\Messaging\QueueSender The queue sender
     */
    protected function getCreateAIntervalTimerSender()
    {
        return $this->createAIntervalTimerSender;
    }

    /**
     * Return's the temporary upload directory specified in the php.ini.
     *
     * @return string The temporary upload directory
     */
    protected function getUploadTmpDir()
    {
        return $this->getApplication()->getBaseDirectory(DIRECTORY_SEPARATOR . ini_get('upload_tmp_dir'));
    }

    /**
     * Returns an ArrayObject with the CSV files that can be imported.
     *
     * @return \ArrayObject An array with the name of CSV files that can be imported
     */
    public function findAll()
    {

        // initialize an array object to load file uploads
        $overviewData = new \ArrayObject();

        // init file iterator on deployment directory
        $fileIterator = new \FilesystemIterator($this->getUploadTmpDir());

        // Iterate through all phar files and extract them to tmp dir
        foreach (new \RegexIterator($fileIterator, '/^.*\\.csv$/') as $importFile) {
            $overviewData->append($importFile->getFilename());
        }

        // return the array with the name of the uploaded files
        return $overviewData;
    }

    /**
     * Uploads the passed file part to the temporary upload directory.
     *
     * @param \AppserverIo\Psr\HttpMessage\PartInterface $fileToUpload   The file part to upload
     * @param \AppserverIo\Lang\Boolean                  $watchDirectory TRUE if the directory has to be watched
     *
     * @return void
     */
    public function upload(PartInterface $fileToUpload, Boolean $watchDirectory)
    {

        // save file to appservers upload tmp folder with tmpname
        $fileToUpload->init();
        $fileToUpload->write(
            tempnam($this->getUploadTmpDir(), 'example_upload_') . '.' . pathinfo($fileToUpload->getFilename(), PATHINFO_EXTENSION)
        );

        // check if we should watch the directory for periodic import
        if ($watchDirectory->booleanValue()) {
            // load the application name
            $applicationName = $this->getApplication()->getName();

            // initialize the message with the name of the directory we want to watch
            $message = new StringMessage($this->getUploadTmpDir());

            // create a new message and send it
            $this->getCreateAIntervalTimerSender()->send($message, false);
        }
    }

    /**
     * Delete the file from the temporary upload directory
     *
     * @param string $filename The name of the file to upload
     *
     * @return void
     */
    public function delete($filename)
    {
        unlink($this->getUploadTmpDir() . DIRECTORY_SEPARATOR . $filename);
    }

    /**
     * Import the file with the passed filename from the temporary upload directory.
     *
     * @param string $filename The name of the file to import
     *
     * @return void
     */
    public function import($filename)
    {

        // load the application name
        $applicationName = $this->getApplication()->getName();

        // initialize the message with the name of the file to import the data from
        $message = new StringMessage($this->getUploadTmpDir() . DIRECTORY_SEPARATOR . $filename);

        // create a new message and send it
        $this->getImportSender()->send($message, false);
    }
}
