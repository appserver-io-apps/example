<?php

/**
 * phpinfo.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */

use Lurker\Event\FilesystemEvent;
use AppserverIo\Properties\Properties;
use AppserverIo\Properties\PropertiesUtil;

/**
 * Defines the available build tasks.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */
class RoboFile extends \Robo\Tasks
{

    /**
     * The process instance for the application server's runner.
     *
     * @var \Robo\Result
     */
    protected $serverProcess = null;

    /**
     * The build properties.
     *
     * @var \AppserverIo\Appserver\PropertiesInterface
     */
    protected $properties = null;

    /**
     * Initialize's the tasks.
     */
    public function __construct()
    {

        // initialize the build properties
        $this->properties = Properties::create();

        // set the default properties
        $this->properties->setProperty('src.dir', 'src');
        $this->properties->setProperty('vendor.dir', '${src.dir}/vendor');
        $this->properties->setProperty('base.dir', '/opt/appserver');
        $this->properties->setProperty('deploy.dir', '${base.dir}/webapps/example');
        $this->properties->setProperty('target.dir', __DIR__ . '/target');
        $this->properties->setProperty('configuration.file', __DIR__ . '/etc/appserver/appserver.xml');
        $this->properties->setProperty('bootstrap.file', '${base.dir}/etc/appserver/conf.d/bootstrap-runner.xml');

        // load properties from build.properties file
        if (file_exists($buildProperties = __DIR__ . '/build.properties')) {
            $this->properties->mergeProperties(Properties::create()->load($buildProperties));
        }

        // load the default build properties
        if (file_exists($buildDefaultProperties = __DIR__ . '/build.default.properties')) {
            $this->properties->mergeProperties(Properties::create()->load($buildDefaultProperties));
        }

        // replace the variables in the properties
        PropertiesUtil::singleton()->replaceProperties($this->properties);
    }

    /**
     * Run's the composer install command.
     *
     * @return void
     */
    public function composerInstall()
    {
        // optimize autoloader with custom path
        $this->taskComposerInstall()
             ->preferDist()
             ->optimizeAutoloader()
             ->run();
    }

    /**
     * Run's the composer update command.
     *
     * @return void
     */
    public function composerUpdate()
    {
        // optimize autoloader with custom path
        $this->taskComposerUpdate()
             ->preferDist()
             ->optimizeAutoloader()
             ->run();
    }

    /**
     * Clean up the environment for a new build.
     *
     * @return void
     */
    public function clean()
    {
        $this->taskDeleteDir($this->properties->getProperty('target.dir'))->run();
    }

    /**
     * Prepare's the environment for a new build.
     *
     * @return void
     */
    public function prepare()
    {
        $this->taskFileSystemStack()->mkdir($this->properties->getProperty('target.dir'))->run();
    }

    /**
     * Calculate's the next version version number based on the actual
     * version defined in the the build.default.properties file.
     *
     * @return void
     */
    public function semverCompare()
    {

        // prepare the environment
        $this->clean();
        $this->prepare();

        // reset the .semver file
        $this->taskGitStack()
            ->exec('checkout .semver')
            ->stopOnFail()
            ->run();

        // clone the tag of the last version defined in the build properties
        $this->taskGitStack()
             ->cloneRepo('git@github.com:wagnert/example.git', sprintf('%s/example', $this->properties->getProperty('target.dir')))
             ->stopOnFail()
             ->pull('origin', $this->properties->getProperty('appserver.webapp.version'))
             ->run();

        // analyze the differences between the two versions and write them to an JSON file
        $this->taskExec(sprintf('%s/bin/php-semver-checker', $this->properties->getProperty('vendor.dir')))
             ->arg('compare')
             ->arg('.')
             ->arg(sprintf('%s/example', $this->properties->getProperty('target.dir')))
             ->arg(sprintf('--to-json=%s/semver.json', $this->properties->getProperty('target.dir')))
             ->run();

        // read the incrementation level from the written JSON file
        $semver = json_decode(file_get_contents(sprintf('%s/semver.json', $this->properties->getProperty('target.dir'))));

        // query whether or not we've to raise the version
        if (($level = strtolower($semver->level)) === 'none') {
            return;
        }

        // increment the .semver file with the new version
        $this->taskSemVer('.semver')
             ->increment($level)
             ->run();
    }

    /**
     * Run's the PHPUnit tests.
     *
     * @return void
     */
    public function runTests()
    {
        $this->taskPHPUnit(sprintf('%s/bin/phpunit', $this->properties->getProperty('vendor.dir')))
             ->bootstrap('bootstrap.php')
             ->configFile('phpunit.xml')
             ->run();
    }

    /**
     * Run's the PHPSpec tests.
     *
     * @return void
     */
    public function runSpec()
    {
        $this->taskPhpspec(sprintf('%s/bin/phpspec', $this->properties->getProperty('vendor.dir')))
             ->format('pretty')
             ->config(sprintf('%s/WEB-INF/phpspec.yml.dist', $this->properties->getProperty('src.dir')))
             ->noInteraction()
             ->run();
    }

    /**
     * Deploy's the application to the application server's webapps dir.
     *
     * @return void
     */
    public function deploy()
    {
        $this->taskCopyDir([$this->properties->getProperty('src.dir') => $this->properties->getProperty('deploy.dir')])->run();
    }

    /**
     * Sync's the application sources from the src directory and
     * the application server's webapps dir, using rsync.
     */
    public function rsync()
    {
        $this->taskRsync()
             ->fromPath('src/')
             ->toPath($this->properties->getProperty('deploy.dir'))
             ->recursive()
             ->excludeVcs()
             ->checksum()
             ->exclude(sprintf('%s/*', $this->properties->getProperty('vendor.dir')))
             ->wholeFile()
             ->verbose()
             ->progress()
             ->humanReadable()
             ->stats()
             ->option('delete')
             ->run();
    }

    /**
     * Stop's the application server's runner.
     *
     * @return void
     */
    public function stop()
    {
        if ($this->serverProcess) {
            $task = $this->serverProcess->getTask();
            $task->__destruct();
        }
    }

    /**
     * Start's the application server's runner.
     *
     * @return void
     */
    public function start()
    {
        $this->serverProcess = $this->taskExec('php /opt/appserver/server.php')
                                    ->arg(sprintf('-b=%s', $this->properties->getProperty('bootstrap.file')))
                                    ->arg(sprintf('-c=%s', $this->properties->getProperty('configuration.file')))
                                    ->dir($this->srcDir)
                                    ->background()
                                    ->run();
    }

    /**
     * Watch the src folder for changes and restart's
     * the application server's runner if necessary.
     *
     * @return void
     */
    public function watch()
    {

        // initially start the application server runner
        $this->start();

        // watch the src directory for any changes
        $this->taskWatch()->monitor($this->properties->getProperty('src.dir'), function(FilesystemEvent $event) {
            // restart the application server's runner
            $this->stop();
            $this->start();
        })->run();
    }
}
