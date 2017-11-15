<?php

/**
 * AppserverIo\Apps\Example\Actions\ImportApplication
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

use Rhumsaa\Uuid\Uuid;
use Psr\Log\LogLevel;
use TechDivision\Import\Utils\LoggerKeys;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Exceptions\LineNotFoundException;
use TechDivision\Import\Exceptions\FileNotFoundException;
use TechDivision\Import\Exceptions\ImportAlreadyRunningException;

/**
 * The M2IF - Simple Application implementation.
 *
 * This is a example application implementation that should give developers an impression
 * on how the M2IF could be used to implement their own Magento 2 importer.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Stateless
 */
class ImportApplication implements ImportApplicationInterface
{

    /**
     * The TechDivision company name as ANSI art.
     *
     * @var string
     */
    protected $ansiArt = ' _______        _     _____  _       _     _
|__   __|      | |   |  __ \(_)     (_)   (_)
   | | ___  ___| |__ | |  | |___   ___ ___ _  ___  _ __
   | |/ _ \/ __| \'_ \| |  | | \ \ / / / __| |/ _ \| \'_ \
   | |  __/ (__| | | | |__| | |\ V /| \__ \ | (_) | | | |
   |_|\___|\___|_| |_|_____/|_| \_/ |_|___/_|\___/|_| |_|
';

    /**
     * The PID for the running processes.
     *
     * @var array
     */
    protected $pid;

    /**
     * The actions unique serial.
     *
     * @var string
     */
    protected $serial;

    /**
     * The plugins to be processed.
     *
     * @var array
     */
    protected $plugins = array();

    /**
     * The flag that stop's processing the operation.
     *
     * @var boolean
     */
    protected $stopped = false;

    /**
     * The filehandle for the PID file.
     *
     * @var resource
     */
    protected $fh;

    /**
     * The array with the system logger instances.
     *
     * @var \Doctrine\Common\Collections\Collection
     * Inject(name="SystemLoggers")
     */
    protected $systemLoggers;

    /**
     * The system configuration.
     *
     * @var \TechDivision\Import\ConfigurationInterface
     * Inject(name="Configuration")
     */
    protected $configuration;

    /**
     * The plugin factory instance.
     *
     * @var \AppserverIo\Apps\Example\Actions\PluginFactory
     * Inject(type="\AppserverIo\Apps\Example\Actions\PluginFactory")
     */
    protected $pluginFactory;

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Psr\Di\ProviderInterface
     * Resource(name="ProviderInterface")
     */
    protected $providerInterface;

    /**
     * The registry instance.
     *
     * @var \AppserverIo\Apps\Example\Services\RegistryProcessor
     * EnterpriseBean
     */
    protected $registryProcessor;

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
     * Return's the container instance.
     *
     * @return \Psr\Container\ContainerInterface The container instance
     */
    public function getContainer()
    {
        return $this->providerInterface;
    }

    /**
     * Return's the system configuration.
     *
     * @return \TechDivision\Import\ConfigurationInterface The system configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Return's the RegistryProcessor instance to handle the running threads.
     *
     * @return \TechDivision\Import\Services\RegistryProcessor The registry processor instance
     */
    public function getRegistryProcessor()
    {
        return $this->registryProcessor;
    }

    /**
     * Return's the plugin factory instance.
     *
     * @return \TechDivision\Import\Plugins\PluginFactoryInterface The plugin factory instance
     */
    public function getPluginFactory()
    {
        return $this->pluginFactory;
    }

    /**
     * Return's the logger with the passed name, by default the system logger.
     *
     * @param string $name The name of the requested system logger
     *
     * @return \Psr\Log\LoggerInterface The logger instance
     * @throws \Exception Is thrown, if the requested logger is NOT available
     */
    public function getSystemLogger($name = LoggerKeys::SYSTEM)
    {

        // query whether or not, the requested logger is available
        if (isset($this->systemLoggers[$name])) {
            return $this->systemLoggers[$name];
        }

        // throw an exception if the requested logger is NOT available
        throw new \Exception(
            sprintf(
                'The requested logger \'%s\' is not available',
                $name
            )
        );
    }

    /**
     * Query whether or not the system logger with the passed name is available.
     *
     * @param string $name The name of the requested system logger
     *
     * @return boolean TRUE if the logger with the passed name exists, else FALSE
     */
    public function hasSystemLogger($name = LoggerKeys::SYSTEM)
    {
        return isset($this->systemLoggers[$name]);
    }

    /**
     * Return's the array with the system logger instances.
     *
     * @return \Doctrine\Common\Collections\Collection The logger instance
     */
    public function getSystemLoggers()
    {
        return $this->systemLoggers;
    }

    /**
     * Return's the unique serial for this import process.
     *
     * @return string The unique serial
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Return's the import processor instance.
     *
     * @return \TechDivision\Import\Services\ImportProcessorInterface The import processor instance
     */
    public function getImportProcessor()
    {
        throw new \Exception('Import processor not available yet!');
    }

    /**
     * The shutdown handler to catch fatal errors.
     *
     * This method is need to make sure, that an existing PID file will be removed
     * if a fatal error has been triggered.
     *
     * @return void
     */
    public function shutdown()
    {

        // check if there was a fatal error caused shutdown
        if ($lastError = error_get_last()) {
            // initialize error type and message
            $type = 0;
            $message = '';
            // extract the last error values
            extract($lastError);
            // query whether we've a fatal/user error
            if ($type === E_ERROR || $type === E_USER_ERROR) {
                // clean-up the PID file
                $this->unlock();
                // log the fatal error message
                $this->log($message, LogLevel::ERROR);
            }
        }
    }

    /**
     * Persist the UUID of the actual import process to the PID file.
     *
     * @return void
     * @throws \Exception Is thrown, if the PID can not be locked or the PID can not be added
     * @throws \TechDivision\Import\Exceptions\ImportAlreadyRunningException Is thrown, if a import process is already running
     */
    public function lock()
    {

        // query whether or not, the PID has already been set
        if ($this->pid === $this->getSerial()) {
            return;
        }

        // if not, initialize the PID
        $this->pid = $this->getSerial();

        // open the PID file
        $this->fh = fopen($filename = $this->getPidFilename(), 'a+');

        // try to lock the PID file exclusive
        if (!flock($this->fh, LOCK_EX|LOCK_NB)) {
            throw new ImportAlreadyRunningException(sprintf('PID file %s is already in use', $filename));
        }

        // append the PID to the PID file
        if (fwrite($this->fh, $this->pid . PHP_EOL) === false) {
            throw new \Exception(sprintf('Can\'t write PID %s to PID file %s', $this->pid, $filename));
        }
    }

    /**
     * Remove's the UUID of the actual import process from the PID file.
     *
     * @return void
     * @throws \Exception Is thrown, if the PID can not be removed
     */
    public function unlock()
    {
        try {
            // remove the PID from the PID file if set
            if ($this->pid === $this->getSerial() && is_resource($this->fh)) {
                // remove the PID from the file
                $this->removeLineFromFile($this->pid, $this->fh);

                // finally unlock/close the PID file
                flock($this->fh, LOCK_UN);
                fclose($this->fh);

                // if the PID file is empty, delete the file
                if (filesize($filename = $this->getPidFilename()) === 0) {
                    unlink($filename);
                }
            }

        } catch (FileNotFoundException $fnfe) {
            $this->getSystemLogger()->notice(sprintf('PID file %s doesn\'t exist', $this->getPidFilename()));
        } catch (LineNotFoundException $lnfe) {
            $this->getSystemLogger()->notice(sprintf('PID %s is can not be found in PID file %s', $this->pid, $this->getPidFilename()));
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Can\'t remove PID %s from PID file %s', $this->pid, $this->getPidFilename()), null, $e);
        }
    }

    /**
     * Remove's the passed line from the file with the passed name.
     *
     * @param string   $line The line to be removed
     * @param resource $fh   The file handle of the file the line has to be removed
     *
     * @return void
     * @throws \Exception Is thrown, if the file doesn't exists, the line is not found or can not be removed
     */
    public function removeLineFromFile($line, $fh)
    {

        // initialize the array for the PIDs found in the PID file
        $lines = array();

        // initialize the flag if the line has been found
        $found = false;

        // rewind the file pointer
        rewind($fh);

        // read the lines with the PIDs from the PID file
        while (($buffer = fgets($fh, 4096)) !== false) {
            // remove the new line
            $buffer = trim($buffer);
            // if the line is the one to be removed, ignore the line
            if ($line === $buffer) {
                $found = true;
                continue;
            }

            // add the found PID to the array
            $lines[] = $buffer;
        }

        // query whether or not, we found the line
        if (!$found) {
            throw new LineNotFoundException(sprintf('Line %s can not be found', $line));
        }

        // empty the file and rewind the file pointer
        ftruncate($fh, 0);
        rewind($fh);

        // append the existing lines to the file
        foreach ($lines as $ln) {
            if (fwrite($fh, $ln . PHP_EOL) === false) {
                throw new \Exception(sprintf('Can\'t write %s to file', $ln));
            }
        }
    }

    /**
     * Process the given operation.
     *
     * @return void
     * @throws \Exception Is thrown if the operation can't be finished successfully
     */
    public function process()
    {

        try {
            // track the start time
            $startTime = microtime(true);

            // @TODO start the transaction

            // prepare the global data for the import process
            $this->setUp();

            // process the plugins defined in the configuration
            /** @var \TechDivision\Import\Configuration\PluginConfigurationInterface $pluginConfiguration */
            foreach ($this->getConfiguration()->getPlugins() as $pluginConfiguration) {
                // query whether or not the operation has been stopped
                if ($this->isStopped()) {
                    break;
                }
                // process the plugin if not
                $this->pluginFactory->createPlugin($pluginConfiguration)->process();
            }

            // tear down the  instance
            $this->tearDown();

            // @TODO commit the transaction

            // track the time needed for the import in seconds
            $endTime = microtime(true) - $startTime;

            // log a message that import has been finished
            $this->log(
                sprintf(
                    'Successfully finished import with serial %s in %f s',
                    $this->getSerial(),
                    $endTime
                ),
                LogLevel::INFO
            );

        } catch (ImportAlreadyRunningException $iare) {
            // tear down
            $this->tearDown();

            // @TODO rollback the transaction

            // finally, if a PID has been set (because CSV files has been found),
            // remove it from the PID file to unlock the importer
            $this->unlock();

            // track the time needed for the import in seconds
            $endTime = microtime(true) - $startTime;

            // log a warning, because another import process is already running
            $this->getSystemLogger()->warning($iare->__toString());

            // log a message that import has been finished
            $this->getSystemLogger()->info(
                sprintf(
                    'Can\'t finish import with serial because another import process is running %s in %f s',
                    $this->getSerial(),
                    $endTime
                )
            );

            // re-throw the exception
            throw $iare;

        } catch (\Exception $e) {
            // tear down
            $this->tearDown();

            // @TODO rollback the transaction

            // finally, if a PID has been set (because CSV files has been found),
            // remove it from the PID file to unlock the importer
            $this->unlock();

            // track the time needed for the import in seconds
            $endTime = microtime(true) - $startTime;

            // log a message that the file import failed
            foreach ($this->systemLoggers as $systemLogger) {
                $systemLogger->error($e->__toString());
            }

            // log a message that import has been finished
            $this->getSystemLogger()->info(
                sprintf(
                    'Can\'t finish import with serial %s in %f s',
                    $this->getSerial(),
                    $endTime
                )
            );

            // re-throw the exception
            throw $e;
        }
    }

    /**
     * Stop processing the operation.
     *
     * @param string $reason The reason why the operation has been stopped
     *
     * @return void
     */
    public function stop($reason)
    {

        // log a message that the operation has been stopped
        $this->log($reason, LogLevel::INFO);

        // stop processing the plugins by setting the flag to TRUE
        $this->stopped = true;
    }

    /**
     * Return's TRUE if the operation has been stopped, else FALSE.
     *
     * @return boolean TRUE if the process has been stopped, else FALSE
     */
    public function isStopped()
    {
        return $this->stopped;
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException When a circular reference is detected
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException          When the service is not defined
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->getContainer()->has($id);
    }

    /**
     * Lifecycle callback that will be inovked before the
     * import process has been started.
     *
     * @return void
     */
    protected function setUp()
    {

        // generate the serial for the new job
        $this->serial = Uuid::uuid4()->__toString();

        // write the TechDivision ANSI art icon to the console
        $this->log($this->ansiArt);

        // log the debug information, if debug mode is enabled
        if ($this->getConfiguration()->isDebugMode()) {
            // log the system's PHP configuration
            $this->log(sprintf('PHP version: %s', phpversion()), LogLevel::DEBUG);
            $this->log('-------------------- Loaded Extensions -----------------------', LogLevel::DEBUG);
            $this->log(implode(', ', $loadedExtensions = get_loaded_extensions()), LogLevel::DEBUG);
            $this->log('--------------------------------------------------------------', LogLevel::DEBUG);

            // write a warning for low performance, if XDebug extension is activated
            if (in_array('xdebug', $loadedExtensions)) {
                $this->log('Low performance exptected, as result of enabled XDebug extension!', LogLevel::WARNING);
            }
        }

        // log a message that import has been started
        $this->log(
            sprintf(
                'Now start import with serial %s [%s => %s]',
                $this->getSerial(),
                $this->getConfiguration()->getEntityTypeCode(),
                $this->getConfiguration()->getOperationName()
            ),
            LogLevel::INFO
        );

        // initialize the status
        $status = array(
            RegistryKeys::STATUS => 1,
            RegistryKeys::BUNCHES => 0,
            RegistryKeys::SOURCE_DIRECTORY => $this->getConfiguration()->getSourceDir(),
            RegistryKeys::MISSING_OPTION_VALUES => array()
        );

        // append it to the registry
        $this->getRegistryProcessor()->setAttribute($this->getSerial(), $status);
    }

    /**
     * Lifecycle callback that will be inovked after the
     * import process has been finished.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->getRegistryProcessor()->removeAttribute($this->getSerial());
    }

    /**
     * Simple method that writes the passed method the the console and the
     * system logger, if configured and a log level has been passed.
     *
     * @param string $msg      The message to log
     * @param string $logLevel The log level to use
     *
     * @return void
     */
    protected function log($msg, $logLevel = null)
    {

        // log the message if a log level has been passed
        if ($logLevel && $systemLogger = $this->getSystemLogger()) {
            $systemLogger->log($logLevel, $msg);
        }
    }

    /**
     * Return's the PID filename to use.
     *
     * @return string The PID filename
     */
    protected function getPidFilename()
    {
        return $this->getConfiguration()->getPidFilename();
    }
}
