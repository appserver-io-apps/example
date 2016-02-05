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

use Doctrine\ORM\Tools\SchemaTool;
use AppserverIo\Apps\Example\Entities\Impl\Product;
use AppserverIo\Collections\ArrayList;
use Doctrine\Common\Collections\ArrayCollection;

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
 * @Stateless
 */
class SchemaProcessor extends AbstractPersistenceProcessor implements SchemaProcessorInterface
{

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Appserver\DependencyInjectionContainer\Interfaces\ProviderInterface $provider
     * @Resource(name="ProviderInterface")
     */
    protected $providerInterface;

    /**
     * A list with default credentials for login testing.
     *
     * @var array
     */
    protected $users = array(
        array('appserver', 'appserver.i0', array('Employee')),
        array('appserver_01', 'appserver.i0', array('Employee')),
        array('appserver_02', 'appserver.i0', array('Employee')),
        array('appserver_03', 'appserver.i0', array('Employee')),
        array('appserver_04', 'appserver.i0', array('Employee')),
        array('appserver_05', 'appserver.i0', array('Employee')),
        array('appserver_06', 'appserver.i0', array('Employee')),
        array('appserver_07', 'appserver.i0', array('Employee')),
        array('appserver_08', 'appserver.i0', array('Employee')),
        array('appserver_09', 'appserver.i0', array('Employee')),
        array('manager', 'appserver.i0', array('Manager')),
        array('sales', 'appserver.i0', array('Sales'))
    );

    /**
     * The default username.
     *
     * @var string
     */
    const DEFAULT_USERNAME = 'appserver';

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
     * Deletes the database schema and creates it new.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function createSchema()
    {

        // load the entity manager and the schema tool
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);

        // load the class definitions
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();

        // drop the schema if it already exists and create it new
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }

    /**
     * Creates the default products.
     *
     * @return void
     */
    public function createDefaultProducts()
    {

        try {
            // load the entity manager
            $entityManager = $this->getEntityManager();

            // create 10 products
            for ($i = 1; $i < 11; $i++) {
                // set user data and save it
                $product = $this->providerInterface->newInstance('\AppserverIo\Apps\Example\Entities\Impl\Product');
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

        } catch (\Exception $e) {
            // log the exception
            $this->getInitialContext()->getSystemLogger()->error($e->__toString());
        }
    }

    /**
     * Creates some default credentials to login.
     *
     * @return void
     */
    public function createDefaultCredentials()
    {

        try {
            // load the entity manager
            $entityManager = $this->getEntityManager();

            // create the default credentials
            foreach ($this->users as $userData) {
                // extract the user data
                list ($username, $password, $roleNames) = $userData;

                // set user data and save it
                $user = $this->providerInterface->newInstance('\AppserverIo\Apps\Example\Entities\Impl\User');
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

        } catch (\Exception $e) {
            // log the exception
            $this->getInitialContext()->getSystemLogger()->error($e->__toString());
        }
    }
}
