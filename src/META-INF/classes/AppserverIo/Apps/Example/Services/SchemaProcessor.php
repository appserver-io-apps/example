<?php

/**
 * AppserverIo\Apps\Example\Services\SchemaProcessor
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

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\DBAL\Schema\SqliteSchemaManager;
use Doctrine\Common\Collections\ArrayCollection;
use AppserverIo\Apps\Example\Entities\Impl\Product;
use AppserverIo\Psr\EnterpriseBeans\Annotations as EPB;

/**
 * A singleton session bean implementation that handles the
 * schema data for Doctrine by using Doctrine ORM itself.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @EPB\Stateless
 */
class SchemaProcessor extends AbstractPersistenceProcessor implements SchemaProcessorInterface
{

    /**
     * The name of the configuration key that contains the database name.
     *
     * @var string
     */
    const PARAM_DBNAME = 'dbname';

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Psr\Di\ProviderInterface $provider
     * @EPB\Resource(type="ProviderInterface")
     */
    protected $providerInterface;

    /**
     * The system logger implementation.
     *
     * @var \AppserverIo\Logger\Logger
     * @EPB\Resource(type="SystemLogger")
     */
    protected $systemLogger;

    /**
     * A list with default credentials for login testing.
     *
     * @var array
     */
    protected $users = array(
        array('appserver', 'appserver.i0', array('Customer')),
        array('appserver_01', 'appserver.i0', array('Customer')),
        array('appserver_02', 'appserver.i0', array('Customer')),
        array('appserver_03', 'appserver.i0', array('Customer')),
        array('appserver_04', 'appserver.i0', array('Customer')),
        array('appserver_05', 'appserver.i0', array('Customer')),
        array('appserver_06', 'appserver.i0', array('Customer')),
        array('appserver_07', 'appserver.i0', array('Customer')),
        array('appserver_08', 'appserver.i0', array('Customer')),
        array('appserver_09', 'appserver.i0', array('Customer')),
        array('guest', 'appserver.i0', array('Guest'))
    );

    /**
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @EPB\PostConstruct
     */
    public function initialize()
    {
        \info(
            sprintf('%s has successfully been invoked by @PostConstruct annotation', __METHOD__)
        );
    }

    /**
     * Return's the system logger instance.
     *
     * @return \AppserverIo\Logger\Logger The sytsem logger instance
     */
    public function getSystemLogger()
    {
        return $this->systemLogger;
    }

    /**
     * Create's the database itself.
     *
     * This quite seems to be a bit strange, because with all databases
     * other than SQLite, we need to remove the database name from the
     * connection parameters BEFORE connecting to the database, as
     * connection to a not existing database fails.
     *
     * @return void
     */
    public function createDatabase()
    {

        try {
            // clone the connection and load the database name
            $connection = clone $this->getEntityManager()->getConnection();
            $dbname = $connection->getDatabase();

            // remove the the database name
            $params = $connection->getParams();
            if (isset($params[SchemaProcessor::PARAM_DBNAME])) {
                unset($params[SchemaProcessor::PARAM_DBNAME]);
            }

            // create a new connection WITHOUT the database name
            $cn = DriverManager::getConnection($params);
            $sm = $cn->getDriver()->getSchemaManager($cn);

            // SQLite doesn't support database creation by a method
            if ($sm instanceof SqliteSchemaManager) {
                return;
            }

            // query whether or not the database already exists
            if (!in_array($dbname, $sm->listDatabases())) {
                $sm->createDatabase($dbname);
            }
        } catch (\Exception $e) {
            \error($e->__toString());
        }
    }

    /**
     * Deletes the database schema and creates it new.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function createSchema()
    {

        try {
            // load the entity manager and the schema tool
            $entityManager = $this->getEntityManager();
            $schemaTool = new SchemaTool($entityManager);

            // load the class definitions
            $classes = $entityManager->getMetadataFactory()->getAllMetadata();

            // create or update the schema
            $schemaTool->updateSchema($classes);
        } catch (\Exception $e) {
            \error($e->__toString());
        }
    }

    /**
     * Creates the default products.
     *
     * @return void
     */
    public function createDefaultProducts()
    {

        // load the entity manager
        $entityManager = $this->getEntityManager();

        // load the product repository
        $repository = $entityManager->getRepository($className = '\AppserverIo\Apps\Example\Entities\Impl\Product');

        // create 10 products
        for ($i = 1; $i < 11; $i++) {
            // query whether or not, the product has already been created
            if ($repository->findOneByProductNumber($i)) {
                continue;
            }

            // set user data and save it
            $product = $this->providerInterface->newInstance($className);
            $product->setName("Product-$i");
            $product->setStatus(Product::STATUS_ACTIVE);
            $product->setUrlKey("product-$i");
            $product->setProductNumber($i);
            $product->setSalesPrice($i);
            $product->setDescription("Description of Product-$i");

            // persist the user
            $entityManager->persist($product);
        }

        // flush the entity manager
        $entityManager->flush();
    }

    /**
     * Creates some default credentials to login.
     *
     * @return void
     */
    public function createDefaultCredentials()
    {

        // load the entity manager
        $entityManager = $this->getEntityManager();

        // load the user repository
        $repository = $entityManager->getRepository($className = '\AppserverIo\Apps\Example\Entities\Impl\User');

        // create the default credentials
        foreach ($this->users as $userData) {
            // extract the user data
            list ($username, $password, $roleNames) = $userData;

            // query whether or not, the user has already been created
            if ($repository->findOneByUsername($username)) {
                continue;
            }

            // set user data and save it
            $user = $this->providerInterface->newInstance($className);
            $user->setEmail(sprintf('%s@appserver.io', $username));
            $user->setUsername($username);
            $user->setUserLocale('en_US');
            $user->setPassword(md5($password));
            $user->setEnabled(true);
            $user->setRate(1000);
            $user->setContractedHours(160);
            $user->setLdapSynced(false);
            $user->setSyncedAt(time());

            // create a collection to store the user's roles
            $roles = new ArrayCollection();

            // create the user's roles
            foreach ($roleNames as $roleName) {
                $role = $this->providerInterface->newInstance('\AppserverIo\Apps\Example\Entities\Impl\Role');
                $role->setUser($user);
                $role->setName($roleName);
                $roles->add($role);
            }

            // set the user's roles
            $user->setRoles($roles);

            // persist the user
            $entityManager->persist($user);
        }

        // flush the entity manager
        $entityManager->flush();
    }
}
