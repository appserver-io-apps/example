<?php

/**
 * AppserverIo\Apps\Example\Doctrine\TreeEntityManagerFactory
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

namespace AppserverIo\Apps\Example\Doctrine;

use Gedmo\DoctrineExtensions;
use Gedmo\Tree\TreeListener;
use Gedmo\Translatable\TranslatableListener;
use Gedmo\Timestampable\TimestampableListener;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use AppserverIo\Psr\Application\ApplicationInterface;
use AppserverIo\Appserver\Core\Api\Node\PersistenceUnitNodeInterface;
use AppserverIo\Appserver\PersistenceContainer\Doctrine\V2\EntityManagerFactory;
use Gedmo\Sluggable\SluggableListener;

/**
 * Factory implementation for a Doctrine EntityManager instance.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class TreeEntityManagerFactory extends EntityManagerFactory
{

    /**
     * Creates a new entity manager instance based on the passed configuration.
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface                 $application         The application instance to create the entity manager for
     * @param \AppserverIo\Appserver\Core\Api\Node\PersistenceUnitNodeInterface $persistenceUnitNode The datasource configuration
     *
     * @return object The entity manager instance
     */
    public static function factory(ApplicationInterface $application, PersistenceUnitNodeInterface $persistenceUnitNode)
    {

        // let the parent object instanciate the entity manager
        $entityManager = parent::factory($application, $persistenceUnitNode);

        // load the metadata driver and the annotation reader
        $metadataDriver = $entityManager->getConfiguration()->getMetadataDriverImpl();
        $annotationReader = $metadataDriver->getReader();

        // create a driver chain for metadata reading
        $driverChain = new MappingDriverChain();

        // NOTE: driver for application Entity can be different, Yaml, Xml or whatever
        // register annotation driver for our application Entity namespace
        $driverChain->addDriver($metadataDriver, 'Entity');

        // load superclass metadata mapping only, into driver chain
        // also registers Gedmo annotations.NOTE: you can personalize it
        DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $annotationReader);

        // initialize the SluggableListener instance
        $sluggableListener = new SluggableListener();
        $sluggableListener->setAnnotationReader($annotationReader);

        // hook the TranslatableListener instance
        $entityManager->getEventManager()->addEventSubscriber($sluggableListener);

        // initialize the TreeListener instance
        $treeListener = new TreeListener();
        $treeListener->setAnnotationReader($annotationReader);

        // hook the TreeListener instance
        $entityManager->getEventManager()->addEventSubscriber($treeListener);

        // initialize the TimestampableListener instance
        $timestampableListener = new TimestampableListener();
        $timestampableListener->setAnnotationReader($annotationReader);

        // hook the TimestampableListener instance
        $entityManager->getEventManager()->addEventSubscriber($timestampableListener);

        // initialize the TranslatableListener instance
        $translatableListener = new TranslatableListener();
        // current translation locale should be set from session or hook later
        // into the listener most important, before entity manager is flushed
        $translatableListener->setTranslatableLocale('en_US');
        $translatableListener->setDefaultLocale('en_US');
        $translatableListener->setAnnotationReader($annotationReader);

        // hook the TranslatableListener instance
        $entityManager->getEventManager()->addEventSubscriber($translatableListener);

        // finally return the entity manager instance
        return $entityManager;
    }
}
