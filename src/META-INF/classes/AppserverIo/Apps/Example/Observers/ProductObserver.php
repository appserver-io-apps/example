<?php

/**
 * AppserverIo\Apps\Example\Observers\ImportReceiver
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

namespace AppserverIo\Apps\Example\Observers;

use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Observers\AbstractObserver;

/**
 * Dummy product observer implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class ProductObserver extends AbstractObserver
{

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * PersistenceUnit(unitName="ExampleEntityManager")
     */
    protected $entityManager;

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Psr\Di\ProviderInterface $provider
     * Resource(name="ProviderInterface")
     */
    protected $providerInterface;

    /**
     * Return's the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManagerInterface The initialized Doctrine entity manager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Will be invoked by the action on the events the listener has been registered for.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject instance
     *
     * @return array The modified row
     */
    public function handle(SubjectInterface $subject)
    {

        // initialize the row
        $this->setSubject($subject);
        $this->setRow($subject->getRow());

        // load the entity manager
        $entityManager = $this->getEntityManager();

        // load the product repository
        $repository = $entityManager->getRepository($className = '\AppserverIo\Apps\Example\Entities\Impl\Product');

        // query whether or not, the product has already been created
        /** @var AppserverIo\Apps\Example\Entities\Impl\Product $found */
        if ($found = $repository->findOneByProductNumber($this->getValue('sku'))) {
            $product = $found;
        } else {
            /** @var AppserverIo\Apps\Example\Entities\Impl\Product $product */
            $product = $this->providerInterface->newInstance($className);
        }

        // set user data and save it
        $product->setProductNumber($this->getValue('sku'));
        $product->setName($this->getValue('name'));
        $product->setStatus($this->getValue('product_online'));
        $product->setUrlKey($this->getValue('url_key'));
        $product->setSalesPrice($this->getValue('price'));
        $product->setDescription($this->getValue('description'));

        // persist the user
        $entityManager->persist($product);

        // flush the entity manager
        $entityManager->flush();

        // return the processed row
        return $this->getRow();
    }
}
