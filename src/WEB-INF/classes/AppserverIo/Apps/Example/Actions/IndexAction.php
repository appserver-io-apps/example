<?php

/**
 * AppserverIo\Apps\Example\Actions\IndexAction
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

use AppserverIo\Apps\Example\Entities\Sample;
use AppserverIo\Apps\Example\Utils\ProxyKeys;
use AppserverIo\Apps\Example\Utils\ContextKeys;
use AppserverIo\Apps\Example\Utils\RequestKeys;
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
class IndexAction extends ExampleBaseAction
{

    /**
     * The relative path, up from the webapp path, to the template to use.
     *
     * @var string
     */
    const INDEX_TEMPLATE = 'static/templates/index.phtml';

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
        $overviewData = $this->getProxy(ProxyKeys::SAMPLE_PROCESSOR)->findAll();
        $this->setAttribute(ContextKeys::OVERVIEW_DATA, $overviewData);
        $servletResponse->appendBodyStream($this->processTemplate(IndexAction::INDEX_TEMPLATE, $servletRequest, $servletResponse));
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     *
     * @throws \Exception
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function loadAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $sampleId = $servletRequest->getParameter(RequestKeys::SAMPLE_ID, FILTER_VALIDATE_INT);
        if ($sampleId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::SAMPLE_ID));
        }

        // load the entity to be edited and attach it to the servlet context
        $viewData = $this->getProxy(ProxyKeys::SAMPLE_PROCESSOR)->load($sampleId);
        $this->setAttribute(ContextKeys::VIEW_DATA, $viewData);

        // reload all entities and render the dialog
        $this->indexAction($servletRequest, $servletResponse);
    }

    /**
     * Deletes the sample entity with the sample ID found in the request and
     * reloads all other entities from the database.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     *
     * @throws \Exception
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function deleteAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $sampleId = $servletRequest->getParameter(RequestKeys::SAMPLE_ID, FILTER_VALIDATE_INT);
        if ($sampleId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::SAMPLE_ID));
        }

        // delete the entity
        $this->getProxy(ProxyKeys::SAMPLE_PROCESSOR)->delete($sampleId);

        // reload all entities and render the dialog
        $this->indexAction($servletRequest, $servletResponse);
    }

    /**
     * Persists the entity data found in the request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function persistAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $sampleId = $servletRequest->getParameter(RequestKeys::SAMPLE_ID, FILTER_VALIDATE_INT);

        // check if the user has a name specified
        if ($name = trim($servletRequest->getParameter(RequestKeys::NAME))) {
            // create a new entity and persist it
            $entity = new Sample();
            $entity->setSampleId((integer) $sampleId);
            $entity->setName($name);
            $this->getProxy(ProxyKeys::SAMPLE_PROCESSOR)->persist($entity);

        } else {
            // if no name has been specified, add an error message
            $this->setAttribute(ContextKeys::ERROR_MESSAGES, array('Please add a name!'));
        }

        // reload all entities and render the dialog
        $this->indexAction($servletRequest, $servletResponse);
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
}
