<?php

/**
 * AppserverIo\Apps\Example\Services\AbstractProcessor
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

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use AppserverIo\Psr\Application\ApplicationInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class AbstractProcessor
{

    /**
     * Datasource name to use.
     *
     * @var string
     */
    protected $datasourceName = 'appserver.io-example-application';

    /**
     * Relative path to the folder with the database entries.
     *
     * @var string
     */
    protected $pathToEntities = 'common/classes/AppserverIo/Apps/Example/Entities';

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * Injects the application into all extending instances.
     *
     * ATTENTION: Will only be used if you activate it in the epb.xml file!
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface $application The application instance
     *
     * @return void
     */
    public function injectApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Initializes the database connection parameters necessary
     * to connect to the database using Doctrine.
     *
     * @return void
     * @PostConstruct
     */
    public function init()
    {

        // prepare the path to the entities
        $absolutePaths = array();
        if ($relativePaths = $this->getPathToEntities()) {
            foreach (explode(PATH_SEPARATOR, $relativePaths) as $relativePath) {
                $absolutePaths[] = $this->getApplication()->getWebappPath() . DIRECTORY_SEPARATOR . $relativePath;
            }
        }

        // register the annotations for the JMS serializer
        AnnotationRegistry::registerAutoloadNamespace(
            'JMS\\Serializer\\Annotation',
            $this->getApplication()->getWebappPath() . DIRECTORY_SEPARATOR . 'vendor/jms/serializer/src'
        );

        // create the database configuration and initialize the entity manager
        $metadataConfiguration = Setup::createAnnotationMetadataConfiguration($absolutePaths, true, null, null, false);

        // iterate over the found database sources
        foreach ($this->getDatasources() as $datasourceNode) {
            // if the datasource is related to the session bean
            if ($datasourceNode->getName() == $this->getDatasourceName()) {
                // initialize the database node
                $databaseNode = $datasourceNode->getDatabase();

                // initialize the connection parameters
                $connectionParameters = array(
                    'driver'   => $databaseNode->getDriver()->getNodeValue()->__toString(),
                    'user'     => $databaseNode->getUser()->getNodeValue()->__toString(),
                    'password' => $databaseNode->getPassword()->getNodeValue()->__toString()
                );

                // initialize the path to the database when we use sqlite for example
                if ($databaseNode->getPath()) {
                    if ($path = $databaseNode->getPath()->getNodeValue()->__toString()) {
                        $connectionParameters['path'] = $this->getApplication()->getWebappPath() . DIRECTORY_SEPARATOR . $path;
                    }
                }

                // add database name if using another PDO driver than sqlite
                if ($databaseNode->getDatabaseName()) {
                    $databaseName = $databaseNode->getDatabaseName()->getNodeValue()->__toString();
                    $connectionParameters['dbname'] = $databaseName;
                }

                // add database host if using another PDO driver than sqlite
                if ($databaseNode->getDatabaseHost()) {
                    $databaseHost = $databaseNode->getDatabaseHost()->getNodeValue()->__toString();
                    $connectionParameters['host'] = $databaseHost;
                }

                // initialize and set the EntityManager instance
                $this->entityManager = EntityManager::create($connectionParameters, $metadataConfiguration);

                // stop foreach loop when we've created the EntityManager instance
                return;
            }
        }
    }

    /**
     * Disconnects the Doctrine EntityManager, because the connection (a resource)
     * can't be serialized between thread (request) instances.
     *
     * @param string $origin The name of the origin that invokes this method
     *
     * @return void
     */
    public function destroy($origin = '@PreDestroy annotation')
    {

        // query wheter we've an entity manager instance
        if ($entityManager = $this->getEntityManager()) {
            // if yes, close the connection
            $entityManager->getConnection()->close();

            // log a message that this method has been invoked
            $this->getInitialContext()->getSystemLogger()->info(
                sprintf('%s has successfully been invoked by the %s', __METHOD__, $origin)
            );
        }
    }

    /**
     * When we've a SFSB, this method will be invoked before re-attaching
     * it to the container.
     *
     * As this is a magic method, in future versions there will be a lifecycle
     * callback that gives you more specific possiblity to investigate on that
     * event.
     *
     * @return void
     */
    public function __sleep()
    {
        $this->destroy('__sleep method');
    }

    /**
     * Return's the path to the doctrine entities.
     *
     * @return string The path to the doctrine entities
     */
    public function getPathToEntities()
    {
        return $this->pathToEntities;
    }

    /**
     * Return's the datasource name to use.
     *
     * @return string The datasource name
     */
    public function getDatasourceName()
    {
        return $this->datasourceName;
    }

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Return's the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManager The initialized Doctrine entity manager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Returns the initial context instance.
     *
     * @return \AppserverIo\Appserver\Core\InitialContext The initial context instance
     */
    public function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
    }

    /**
     * Return's the system configuration
     *
     * @return \AppserverIo\Configuration\Interfaces\NodeInterface The system configuration
     */
    public function getSystemConfiguration()
    {
        return $this->getInitialContext()->getSystemConfiguration();
    }

    /**
     * Return's the array with the datasources.
     *
     * @return array The array with the datasources
     */
    public function getDatasources()
    {
        return $this->getSystemConfiguration()->getDatasources();
    }
}
