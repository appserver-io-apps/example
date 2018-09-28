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

use Doctrine\Common\Collections\ArrayCollection;
use AppserverIo\Apps\Example\Entities\Impl\Product;
use AppserverIo\Psr\EnterpriseBeans\Annotations as EPB;
use AppserverIo\Console\Server\Services\SchemaProcessorTrait;

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
     * Trait that provides schema managing functionality.
     *
     * @var AppserverIo\Console\Server\Services\SchemaProcessorTrait
     */
    use SchemaProcessorTrait;
    
    /**
     * The application instance.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @EPB\Resource(type="ApplicationInterface")
     */
    protected $application;
    
    /**
     * The DI provider instance.
     *
     * @var \AppserverIo\Psr\Di\ProviderInterface
     * @EPB\Resource(type="ProviderInterface")
     */
    protected $provider;
    
    /**
     * The timer service context instance.
     *
     * @var \AppserverIo\Psr\EnterpriseBeans\TimerServiceContextInterface
     * @EPB\Resource(type="TimerServiceContextInterface")
     */
    protected $timerServiceContext;
    
    /**
     * The object manager instance.
     *
     * @var \AppserverIo\Psr\Di\ObjectManagerInterface
     * @EPB\Resource(type="ObjectManagerInterface")
     */
    protected $objectManager;

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
     * Returns the application instance.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The initialized Doctrine entity manager
     */
    protected function getApplication()
    {
        return $this->application;
    }
    
    /**
     * Returns the DI provider instance.
     *
     * @return \AppserverIo\Psr\Di\ProviderInterface The DI provider instance
     */
    protected function getProvider()
    {
        return $this->provider;
    }
    
    /**
     * Returns the object manager instance.
     *
     * @return \AppserverIo\Psr\Di\ObjectManagerInterface The object manager instance
     */
    protected function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /**
     * Returns the timer service context instance.
     *
     * @return \AppserverIo\Psr\EnterpriseBeans\TimerServiceContextInterface The timer service context instance
     */
    protected function getTimerServiceContext()
    {
        return $this->timerServiceContext;
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
            $product = $this->getProvider()->newInstance($className);
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
            $user = $this->getProvider()->newInstance($className);
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
                $role = $this->getProvider()->newInstance('\AppserverIo\Apps\Example\Entities\Impl\Role');
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
