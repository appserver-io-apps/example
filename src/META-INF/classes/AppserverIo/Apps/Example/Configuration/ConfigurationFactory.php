<?php

/**
 * AppserverIo\Apps\Example\Configuration\ConfigurationFactory
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

namespace AppserverIo\Apps\Example\Configuration;

/**
 * The configuration factory implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class ConfigurationFactory extends \TechDivision\Import\Configuration\Jms\ConfigurationFactory
{

    /**
     * The configuration filename.
     *
     * @var string
     */
    const CONFIGURATION_FILENAME = 'META-INF/data/import/conf/techdivision-import.json';

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * Factory implementation to create a new initialized configuration instance.
     *
     * @return \TechDivision\Import\ConfigurationInterface The configuration instance
     * @throws \Exception Is thrown, if the configuration can not be initialized
     */
    public function createConfiguration()
    {

        // initialize the actual vendor directory and entity type code
        $vendorDir = $this->getApplication()->getWebappPath() . DIRECTORY_SEPARATOR . 'vendor';

        // initialize the path of the JMS serializer directory, relative to the vendor directory
        $jmsDir = DIRECTORY_SEPARATOR . 'jms' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'src';

        // try to find the path to the JMS Serializer annotations
        if (!file_exists($annotationDir = $vendorDir . DIRECTORY_SEPARATOR . $jmsDir)) {
            // stop processing, if the JMS annotations can't be found
            throw new \Exception(
                sprintf(
                    'The jms/serializer libarary can not be found in one of "%s"',
                    implode(', ', $vendorDir)
                )
            );
        }

        // register the autoloader for the JMS serializer annotations
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            $annotationDir
        );

        // initialize the configuration filename
        $filename = sprintf('%s/%s', $this->getApplication()->getWebappPath(), ConfigurationFactory::CONFIGURATION_FILENAME);

        // initialize the JMS serializer, load and return the configuration
        $instance = parent::factory($filename, pathinfo($filename, PATHINFO_EXTENSION));

        // extend the plugins with the main configuration instance
        /** @var \TechDivision\Import\Cli\Configuration\Plugin $plugin */
        foreach ($instance->getPlugins() as $plugin) {
            // set the configuration instance on the plugin
            $plugin->setConfiguration($instance);

            // query whether or not the plugin has subjects configured
            if ($subjects = $plugin->getSubjects()) {
                // extend the plugin's subjects with the main configuration instance
                /** @var \TechDivision\Import\Cli\Configuration\Subject $subject */
                foreach ($subjects as $subject) {
                    // set the configuration instance on the subject
                    $subject->setConfiguration($instance);
                }
            }
        }

        // finally return the instance
        return $instance;
    }
}
