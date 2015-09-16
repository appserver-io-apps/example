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

use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Apps\Example\Utils\ContextKeys;
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
 *
 * @Path(name="/upload")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/upload.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/upload.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class UploadAction extends DispatchAction
{

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Renders an upload dialoge with a select and submit button.
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
        return ActionInterface::INPUT;
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     * @see IndexServlet::indexAction()
     *
     * @Action(name="/upload")
     */
    public function uploadAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            // check if a file has been selected
            if ($fileToUpload = $servletRequest->getPart(RequestKeys::FILE_TO_UPLOAD)) {
                // save file to appserver's upload tmp folder with tmpname
                $fileToUpload->init();
                $fileToUpload->write(tempnam(ini_get('upload_tmp_dir'), 'example_upload_'));

            } else {
                // if no file has been selected, add an error message
                $this->setAttribute(ContextKeys::ERROR_MESSAGES, array('Please select a file to upload!'));
            }

        } catch (\Exception $e) {
            // append the exception the response body
            $this->addFieldError('critical', $e->getMessage());
            // action invocation has failed
            return ActionInterface::FAILURE;
        }

        // action invocation has been successfull
        return ActionInterface::INPUT;
    }
}
