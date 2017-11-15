<?php

/**
 * AppserverIo\Apps\Example\Actions\ImportAction
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

use AppserverIo\Lang\String;
use AppserverIo\Lang\Boolean;
use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example servlet that imports .csv files by uploading them and sends a message to the
 * message queue to start the import.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Path(name="/import")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/import.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/import.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class ImportAction extends DispatchAction
{

    /**
     * The ImportProcessor instance to handle the import functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\ImportProcessor
     * @EnterpriseBean
     */
    protected $importProcessor;

    /**
     * Returns the ImportProcessor instance to handle the import functionality.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance
     */
    public function getImportProcessor()
    {
        return $this->importProcessor;
    }

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Loads all .csv file uploads and attaches it to the servlet context ready to be rendered
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

        // attach a list with all files that can be imported to the request
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getImportProcessor()->findAll());
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/import")
     */
    public function importAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // try to load the filename that has to imported
        if ($filename = $servletRequest->getParameter(RequestKeys::FILENAME, FILTER_SANITIZE_STRING)) {
            // import the file with the name passed as request parameter
            $this->getImportProcessor()->import($filename);

        } else {
            // if no file has been selected, add an error message
            throw new \Exception('Please select a file to import!');
        }

        // attach a list with all files that can be imported to the request
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getImportProcessor()->findAll());
    }

    /**
     * Handles a .csv file upload by storing the uploaded file in the directory specified
     * by the php.ini configuration upload_tmp_dir.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/upload")
     */
    public function uploadAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if a file has been selected
        if ($fileToUpload = $servletRequest->getPart(RequestKeys::FILE_TO_UPLOAD)) {
            // query whether the directory has to be watched or not
            if ($watchDirectory = $servletRequest->getParameter(RequestKeys::WATCH_DIRECTORY, FILTER_VALIDATE_BOOLEAN)) {
                $watchDirectory = Boolean::valueOf(new String($watchDirectory));
            } else {
                // initialize the flag if the directory has to be watched
                $watchDirectory = new Boolean(false);
            }

            // if yes, upload the file to be imported and watch the Directory
            $this->getImportProcessor()->upload($fileToUpload, $watchDirectory);

        } else {
            // if no file has been selected, add an error message
            throw new \Exception('Please select a file to upload!');
        }

        // attach a list with all files that can be imported to the request
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getImportProcessor()->findAll());
    }

    /**
     * Deletes the uploaded .csv file from the directory specified by the php.ini configuration upload_tmp_dir.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/delete")
     */
    public function deleteAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // delete the file from the temporary upload directory
        $this->getImportProcessor()->delete($servletRequest->getParameter(RequestKeys::FILENAME, FILTER_SANITIZE_STRING));

        // attach a list with all files that can be imported to the request
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getImportProcessor()->findAll());
    }
}
