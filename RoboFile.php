<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

use Lurker\Event\FilesystemEvent;
use AppserverIo\Properties\Properties;
use AppserverIo\Properties\PropertiesUtil;

class RoboFile extends \Robo\Tasks
{

    protected $serverProcess = null;

    protected $result = null;

    protected $srcDir = 'src';

    protected $vendorDir = 'src/vendor';

    protected $baseDir = '/opt/appserver';

    protected $deployDir = '/opt/appserver/webapps/example';

    protected $configurationFile = __DIR__ . '/etc/appserver/appserver.xml';

    protected $bootstrapFile = '/opt/appserver/etc/appserver/conf.d/bootstrap-runner.xml';

    protected $containers = array(
        'appdata'
    );

    public function __construct()
    {

        $this->properties = Properties::create();

        $this->properties->setProperty('src.dir', 'src');
        $this->properties->setProperty('vendor.dir', '${src.dir}/vendor');
        $this->properties->setProperty('base.dir', '/opt/appserver');
        $this->properties->setProperty('deploy.dir', '${base.dir}/webapps/example');
        $this->properties->setProperty('configuration.file', __DIR__ . '/etc/appserver/appserver.xml');
        $this->properties->setProperty('bootstrap.file', __DIR__ . '/opt/appserver/etc/appserver/conf.d/bootstrap-runner.xml');

        $this->properties->merge(Properties::create()->load(__DIR__ . '/build.properties'));
        $this->properties->merge(Properties::create()->load(__DIR__ . '/build.default.properties'));

        PropertiesUtil::singleton()->replaceProperties($this->properties);
    }

    public function composerInstall()
    {
        // optimize autoloader with custom path
        $this->taskComposerInstall()
             ->preferDist()
             ->optimizeAutoloader()
             ->run();
    }

    public function composerUpdate()
    {
        // optimize autoloader with custom path
        $this->taskComposerUpdate()
             ->preferDist()
             ->optimizeAutoloader()
             ->run();
    }

    public function semver()
    {
        $this->taskSemVer('.semver')
             ->increment()
             ->run();
    }

    public function runTests()
    {
        $this->taskPHPUnit(sprintf('%s/vendor/bin/phpunit', $this->srcDir))
             ->bootstrap('bootstrap.php')
             ->configFile('phpunit.xml')
             ->run();
    }

    public function runSpec()
    {
        $this->taskPhpspec(sprintf('%2/vendor/bin/phpspec', $this->srcDir))
             ->format('pretty')
             ->config('src/WEB-INF/phpspec.yml.dist')
             ->noInteraction()
             ->run();
    }

    public function deploy()
    {
        $this->taskCopyDir([$this->srcDir => '/opt/appserver/webapps/example'])->run();
    }

    public function rsync()
    {

        $this->taskRsync()
             ->fromPath('src/')
             ->toPath('/opt/appserver/webapps/example')
             ->recursive()
             ->excludeVcs()
             ->checksum()
             ->exclude(sprintf('%s/*', $this->vendorDir))
             ->wholeFile()
             ->verbose()
             ->progress()
             ->humanReadable()
             ->stats()
             ->option('delete')
             ->run();
    }

    public function stop()
    {

        if ($this->serverProcess) {
            $task = $this->serverProcess->getTask();
            $task->__destruct();
        }
    }

    public function start()
    {

        $this->serverProcess = $this->taskExec('php /opt/appserver/server.php')
                                    ->arg(sprintf('-b=%s', $this->bootstrapFile))
                                    ->arg(sprintf('-c=%s', $this->configurationFile))
                                    ->dir($this->srcDir)
                                    ->background()
                                    ->run();
    }

    public function watch()
    {

        $this->start();

        $this->taskWatch()->monitor($this->srcDir, function(FilesystemEvent $event) {

            $this->stop();
            $this->start();

        })->run();
    }

    /**
     * Deploy's the source files to the docker containers.
     *
     * @return void
     */
    public function dockerDeploy()
    {
        foreach ($this->containers as $container) {
            $this->taskExec("docker cp src $container:/opt/appserver/webapps/example/")->run();
        }
    }

    public function dockerStop()
    {

        $this->taskDockerStop('example')
             ->run();

        $this->taskDockerRemove('example')
             ->run();
    }

    public function dockerRun()
    {

         $this->taskDockerBuild()
              ->tag('example')
              ->run();

         $this->taskDockerRun('example')
              ->interactive()
              ->name('example')
              ->option('--rm')
              ->run();
    }

    public function dockerWatch()
    {

        $this->dockerRun();

        $this->taskWatch()
             ->monitor('src', function () {
                 $this->dockerStop();
                 $this->dockerRun();
             }
        )->run();
    }

    /**
     * Synchronizes the containers when a file changes.
     *
     * @return void
     */
    public function dockerSync()
    {

        $this->taskWatch()
            ->monitor('src', function(FilesystemEvent $event) {

                foreach ($this->containers as $container) {

                    $relativePath = str_replace(__DIR__ . '/src/', '', $event->getResource());

                    $this->taskExec(
                        sprintf(
                            'docker exec -t %s mkdir -p /opt/appserver/webapps/example/%s',
                            $container,
                            dirname($relativePath)
                        )
                    )->run();

                    $this->taskExec(
                        sprintf(
                            'docker cp %s %s:/opt/appserver/webapps/example/%s',
                            $event->getResource(),
                            $container,
                            $relativePath
                        )
                    )->run();
                }
            }

        )->run();
    }
}
