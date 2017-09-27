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
use AppserverIo\Apps\Example\Entities\Impl\Category;

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
     * The name of the configuration key that contains the database name.
     *
     * @var string
     */
    const PARAM_DBNAME = 'dbname';

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Psr\Di\ProviderInterface $provider
     * @Resource(name="ProviderInterface")
     */
    protected $providerInterface;

    /**
     * The system logger implementation.
     *
     * @var \AppserverIo\Logger\Logger
     * @Resource(lookup="php:global/log/System")
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
     * The default categories.
     *
     * @var array
     */
    protected $categories = array(
        array(
            'title' => 'Root',
            'description' => 'Description for Root Category.',
            'translations' => array(
                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Root'),
                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die "Root Kategorie".')
            ),
            'children' => array(
                array(
                    'title' => 'Default Category',
                    'description' => 'Description for Default Category.',
                    'translations' => array(
                        array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Kategorie Standard'),
                        array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Standard".')
                    ),
                    'children' => array(
                        array(
                            'title' => "What's New",
                            'description' => "Description for What's New.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Was ist neu'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Was ist neu".')
                            )
                        ),
                        array(
                            'title' => "Women",
                            'description' => "Description for Women.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Was ist neu'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Frauen".')
                            )
                        ),
                        array(
                            'title' => "Men",
                            'description' => "Description for Men.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Männer'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Männer".')
                            )
                        ),
                        array(
                            'title' => "Gear",
                            'description' => "Description for Gear.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Ausrüstung'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Ausrüstung".')
                            )
                        ),
                        array(
                            'title' => "Collections",
                            'description' => "Description for Collections.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Kollektion'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Kollektion".')
                            )
                        ),
                        array(
                            'title' => "Training",
                            'description' => "Description for Training.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Training'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Training".')
                            )
                        ),
                        array(
                            'title' => "Promotions",
                            'description' => "Description for Promotions.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Aktion'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Aktion".')
                            )
                        ),
                        array(
                            'title' => "Sale",
                            'description' => "Description for Sale.",
                            'translations' => array(
                                array('locale' => 'de_DE', 'field' => 'title', 'value' => 'Abverkauf'),
                                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Beschreibung für die Kategorie "Abverkauf".')
                            )
                        )
                    )
                )
            )
        )
    );

    /**
     * The default products.
     *
     * @var array
     */
    protected $products = array(
        array(
            'name' => 'Joust Duffle Bag',
            'description' => 'The sporty Joust Duffle Bag can\'t be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it\'s ideal for athletes with places to go.',
            'status' => 1,
            'urlKey' => 'joust-duffle-bag',
            'productNumber' => '24-MB01',
            'salesPrice' => 101.00,
            'translations' => array(
                array('locale' => 'de_DE', 'field' => 'name', 'value' => 'Joust Duffle Bag'),
                array('locale' => 'de_DE', 'field' => 'description', 'value' => 'Die sportliche Joust Duffle Bag ist praktisch unschlagbar - weder im Studio, auf dem Gepäckband oder sonstwo.'),
                array('locale' => 'de_DE', 'field' => 'urlKey', 'value' => 'joust-duffle-bag')
            ),
            'categories' => array(
                'root/default-category/gear'
            )
        )
    );

    /**
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @PostConstruct
     */
    public function initialize()
    {
        $this->getSystemLogger()->info(
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
            $this->getSystemLogger()->error($e->__toString());
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
            $this->getSystemLogger()->error($e->__toString());
        }
    }

    /**
     * Creates default categories.
     *
     * @return void
     */
    public function createDefaultCategories()
    {

        // iterate over the root categories and create the category tree
        foreach ($this->categories as $rootCategory) {
            $this->createCategory($rootCategory);
        }

        // load and flush the entity manager
        $this->getEntityManager()->flush();
    }

    /**
     * Recursive method to create a category and it's children.
     *
     * @param array $cat The category that has to be created
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Category|null $root The root category
     */
    protected function createCategory(array $cat, Category $parent = null)
    {

        // load the entity manager
        $entityManager = $this->getEntityManager();

        // load the skillmatrix master repository
        $repository = $entityManager->getRepository($className = 'AppserverIo\Apps\Example\Entities\Impl\Category');

        // check if the category already exists
        /** @var AppserverIo\Apps\Example\Entities\Impl\Category $found */
        if ($found = $repository->findOneByTitle($cat['title'])) {
            $category = $found;
        } else {
            /** @var AppserverIo\Apps\Example\Entities\Impl\Category $category */
            $category = $this->providerInterface->newInstance($className);
        }

        // if a NO root category has been passed, THIS is a root category
        if ($parent !== null) {
            $category->setParent($parent);
        }

        // set the category's values
        $category->setTitle($cat['title']);
        $category->setDescription($cat['description']);

        // translate the category
        foreach ($cat['translations'] as $trans) {
            $this->getEntityManager()
                 ->getRepository('AppserverIo\Apps\Example\Entities\Impl\CategoryTranslation')
                 ->translate($category, $trans['field'], $trans['locale'], $trans['value']);
        }

        // persist the category BEFORE persisting the children
        $entityManager->persist($category);

        // create the category's children
        if (isset($cat['children'])) {
            foreach ($cat['children'] as $child) {
                $this->createCategory($child, $category);
            }
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
        foreach ($this->products as $prod) {
            // query whether or not, the product has already been created
            /** @var AppserverIo\Apps\Example\Entities\Impl\Product $found */
            if ($found = $repository->findOneByProductNumber($prod['productNumber'])) {
                $product = $found;
            } else {
                /** @var AppserverIo\Apps\Example\Entities\Impl\Product $product */
                $product = $this->providerInterface->newInstance($className);
            }

            // set user data and save it
            $product->setName($prod['name']);
            $product->setStatus($prod['status']);
            $product->setUrlKey($prod['urlKey']);
            $product->setProductNumber($prod['productNumber']);
            $product->setSalesPrice($prod['salesPrice']);
            $product->setDescription($prod['description']);

            // translate the category
            foreach ($prod['translations'] as $trans) {
                $this->getEntityManager()
                     ->getRepository('AppserverIo\Apps\Example\Entities\Impl\ProductTranslation')
                     ->translate($product, $trans['field'], $trans['locale'], $trans['value']);
            }

            // relate with the defined categories
            foreach ($prod['categories'] as $cat) {
                // try to load the category by the generated slug
                /** @var AppserverIo\Apps\Example\Entities\Impl\Category $category */
                $category = $this->getEntityManager()
                                 ->getRepository('AppserverIo\Apps\Example\Entities\Impl\Category')
                                 ->findOneBySlug($cat);

                // if a category can be found, relate it with the product
                if ($category) {
                    error_log("Add category " . $category->getSlug() . " to product " . $product->getProductNumber());
                    $product->addCategory($category);
                }
            }

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
