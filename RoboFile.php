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
        $this->properties->setProperty('target.dir', __DIR__ . '/target');
        $this->properties->setProperty('configuration.file', __DIR__ . '/etc/appserver/appserver.xml');
        $this->properties->setProperty('bootstrap.file', '${base.dir}/etc/appserver/conf.d/bootstrap-runner.xml');

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

    public function semverCompare()
    {

        $this->taskDeleteDir($this->properties->getProperty('target.dir'))->run();
        $this->taskFileSystemStack()->mkdir($this->properties->getProperty('target.dir'));

        $this->taskGitStack()
             ->cloneRepo('git@github.com:wagnert/example.git', sprintf('%s/example', $this->properties->getProperty('target.dir')))
             ->stopOnFail()
             ->pull('origin', '2.1.11')
             ->run();

        $this->taskExec(sprintf('%s/bin/php-semver-checker', $this->properties->getProperty('vendor.dir')))
             ->arg('compare')
             ->arg('.')
             ->arg(sprintf('%s/example', $this->properties->getProperty('target.dir')))
             ->arg(sprintf('--to-json=%s/semver.json', $this->properties->getProperty('target.dir')))
             ->run();

        $semver = json_decode(file_get_contents(sprintf('%s/semver.json', $this->properties->getProperty('target.dir'))));

        $this->taskSemVer('.semver')
             ->increment(strtolower($semver->level))
             ->run();
    }

    public function runTests()
    {
        $this->taskPHPUnit(sprintf('%s/bin/phpunit', $this->properties->getProperty('vendor.dir')))
             ->bootstrap('bootstrap.php')
             ->configFile('phpunit.xml')
             ->run();
    }

    public function runSpec()
    {
        $this->taskPhpspec(sprintf('%s/bin/phpspec', $this->properties->getProperty('vendor.dir')))
             ->format('pretty')
             ->config(sprintf('%s/WEB-INF/phpspec.yml.dist', $this->properties->getProperty('src.dir')))
             ->noInteraction()
             ->run();
    }

    public function deploy()
    {
        $this->taskCopyDir([$this->properties->getProperty('src.dir') => $this->properties->getProperty('deploy.dir')])->run();
    }

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
                                    ->arg(sprintf('-b=%s', $this->properties->getProperty('bootstrap.file')))
                                    ->arg(sprintf('-c=%s', $this->properties->getProperty('configuration.file')))
                                    ->dir($this->srcDir)
                                    ->background()
                                    ->run();
    }

    public function watch()
    {

        $this->start();

        $this->taskWatch()->monitor($this->properties->getProperty('src.dir'), function(FilesystemEvent $event) {

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
            $this->taskExec(sprintf("docker cp src $container:%s/", $this->properties->getProperty('deploy.dir')))->run();
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
                            'docker exec -t %s mkdir -p %s/%s',
                            $container,
                            $this->properties->getProperty('deploy.dir'),
                            dirname($relativePath)
                        )
                    )->run();

                    $this->taskExec(
                        sprintf(
                            'docker cp %s %s:%s/%s',
                            $event->getResource(),
                            $container,
                            $this->properties->getProperty('deploy.dir'),
                            $relativePath
                        )
                    )->run();
                }
            }

        )->run();
    }
}
