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

use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Apps\Example\Entities\Impl\Sample;
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
 *
 * @Path(name="/index")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/index.dhtml", type="ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/index.dhtml", type="ServletDispatcherResult")
 * })
 *
 */
class IndexAction extends DispatchAction
{

    /**
     * The CartProcessor instance to handle the sample functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\SampleProcessor
     * @EnterpriseBean
     */
    protected $sampleProcessor;

    /**
     * Returns the SampleProcessor instance to handle the sample funcionality.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance
     */
    public function getSampleProcessor()
    {
        return $this->sampleProcessor;
    }

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * Loads all sample data and attaches it to the servlet context ready to be rendered
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
        // append the sample data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getSampleProcessor()->findAll());
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
     * @throws \Exception
     *
     * @Action(name="/load")
     */
    public function loadAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $sampleId = $servletRequest->getParameter(RequestKeys::SAMPLE_ID, FILTER_VALIDATE_INT);
        if ($sampleId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::SAMPLE_ID));
        }

        // load the entity to be edited and attach it to the servlet context
        $viewData = $this->getSampleProcessor()->load($sampleId);
        $servletRequest->setAttribute(RequestKeys::VIEW_DATA, $viewData);

        // append the sample data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getSampleProcessor()->findAll());
    }

    /**
     * Deletes the sample entity with the sample ID found in the request and
     * reloads all other entities from the database.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @throws \Exception
     *
     * @Action(name="/delete")
     */
    public function deleteAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if the necessary params has been specified and are valid
        $sampleId = $servletRequest->getParameter(RequestKeys::SAMPLE_ID, FILTER_VALIDATE_INT);
        if ($sampleId == null) {
            throw new \Exception(sprintf('Can\'t find requested %s', RequestKeys::SAMPLE_ID));
        }

        // delete the entity
        $this->getSampleProcessor()->delete($sampleId);

        // append the sample data to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getSampleProcessor()->findAll());
    }

    /**
     * Persists the entity data found in the request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/persist")
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
            $this->getSampleProcessor()->persist($entity);

            // append the sample data to the request attributes
            $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getSampleProcessor()->findAll());
        } else {
            // if no name has been specified, add an error message
            $this->addFieldError(RequestKeys::NAME, 'Please add a name!');
        }
    }

    /**
     * Deletes all samples from the database.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/deleteAll")
     */
    public function deleteAllAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // remove all samples from the database and append an empty array to the request attributes
        $servletRequest->setAttribute(RequestKeys::OVERVIEW_DATA, $this->getSampleProcessor()->deleteAll());
    }
}
