<?php

/**
 * AppserverIo\Apps\Example\Services\SampleProcessor
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

use AppserverIo\Apps\Example\Entities\Impl\Sample;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateless
 */
class SampleProcessor extends AbstractPersistenceProcessor implements SampleProcessorInterface
{

    /**
     * The user processor instance.
     *
     * @var \AppserverIo\Apps\Example\Services\UserProcessor
     * @EnterpriseBean(name="UserProcessor")
     */
    protected $userProcessor;

    /**
     * The user processor instance.
     *
     * @var \AppserverIo\Psr\EnterpriseBeans\TimerServiceContextInterface
     * @Resource(name="TimerServiceContextInterface")
     */
    protected $timerServiceContextInterface;

    /**
     * A test class that has to be injected.
     *
     * @var \AppserverIo\Apps\Example\Services\SomeTest
     */
    protected $someTest;

    /**
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @PostConstruct
     */
    public function initialize()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostConstruct annotation', __METHOD__)
        );
    }

    /**
     * Injects the user processor into this instance.
     *
     * ATTENTION: Will only be used if you activate it in the epb.xml file!
     *
     * @param \AppserverIo\Apps\Example\Services\UserProcessor $userProcessor The user processor instance
     *
     * @return void
     */
    public function injectUserProcessor($userProcessor)
    {
        $this->userProcessor = $userProcessor;
    }

    /**
     * Injects the timer service for this instance.
     *
     * ATTENTION: Will only be used if you activate it in the epb.xml file!
     *
     * @param \AppserverIo\Psr\EnterpriseBeans\TimerServiceContextInterface $timerService The timer service instance
     *
     * @return void
     */
    public function injectTimerService($timerService)
    {
        $this->timerService = $timerService;
    }

    /**
     * Loads and returns the entity with the ID passed as parameter.
     *
     * @param integer $id The ID of the entity to load
     *
     * @return object The entity
     */
    public function load($id)
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->find('AppserverIo\Apps\Example\Entities\Impl\Sample', $id);
    }

    /**
     * Persists the passed entity.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Sample $entity The entity to persist
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Sample The persisted entity
     */
    public function persist(Sample $entity)
    {
        // load the entity manager
        $entityManager = $this->getEntityManager();
        // check if a detached entity has been passed
        if ($entity->getSampleId()) {
            $merged = $entityManager->merge($entity);
            $entityManager->persist($merged);
        } else {
            $entityManager->persist($entity);
        }
        // flush the entity manager
        $entityManager->flush();
        // and return the entity itself
        return $entity;
    }

    /**
     * Deletes the entity with the passed ID.
     *
     * @param integer $id The ID of the entity to delete
     *
     * @return array An array with all existing entities
     */
    public function delete($id)
    {

        // delete the entity with the passed ID
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entityManager->merge($this->load($id)));
        $entityManager->flush();

        // load and return all data
        return $this->findAll();
    }

    /**
     * Deletes all entities from the database.
     *
     * @return array An empty array
     */
    public function deleteAll()
    {

        // deletes all samples from the table
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('DELETE FROM AppserverIo\Apps\Example\Entities\Impl\Sample s WHERE s.sampleId > 0');
        $query->execute();

        // load and return all data
        return $this->findAll();
    }

    /**
     * Returns an array with all existing entities.
     *
     * @param integer $limit  The maxium number of rows to return
     * @param integer $offset The row to start with
     *
     * @return array An array with all existing entities
     */
    public function findAll($limit = 100, $offset = 0)
    {
        // load all entities
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\Sample');
        return $repository->findBy(array(), array(), $limit, $offset);
    }

    /**
     * Returns an array with the entities with the IDs passed as filter.
     *
     * @param array $filter The filter
     *
     * @return array An array with entities matching the filter with the passed IDs
     */
    public function findByFilter(array $filter)
    {
        // load the entities with the IDs passed as filter
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('AppserverIo\Apps\Example\Entities\Impl\Sample');
        return $repository->findBy(array('sampleId' => $filter));
    }
}
