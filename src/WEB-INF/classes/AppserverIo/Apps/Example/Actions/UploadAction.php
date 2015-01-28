<?php

/**
 * AppserverIo\Apps\Example\Actions\UploadAction
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

use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example servlet implementation that handles an upload request.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class UploadAction extends ExampleBaseAction
{

    /**
     * The relative path, up from the webapp path, to the template to use.
     *
     * @var string
     */
    const UPLOAD_TEMPLATE = 'static/templates/upload.phtml';

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Renders an upload dialoge with a select and submit button.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     */
    public function indexAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        $servletResponse->appendBodyStream($this->processTemplate(UploadAction::UPLOAD_TEMPLATE, $servletRequest, $servletResponse));
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     * @see IndexServlet::indexAction()
     */
    public function uploadAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if a file has been selected
        if ($fileToUpload = $servletRequest->getPart(RequestKeys::FILE_TO_UPLOAD)) {
            // save file to appservers upload tmp folder with tmpname
            $fileToUpload->init();
            $fileToUpload->write(tempnam(ini_get('upload_tmp_dir'), 'example_upload_'));

        } else {
            // if no file has been selected, add an error message
            $this->setAttribute(ContextKeys::ERROR_MESSAGES, array('Please select a file to upload!'));
        }

        // after the successfull upload, render the template again
        $this->indexAction($servletRequest, $servletResponse);
    }
}
