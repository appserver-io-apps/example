<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

use Lurker\Event\FilesystemEvent;

class RoboFile extends \Robo\Tasks
{

    protected $containers = array(
        'appdata'
    );

    /**
     * Deploy's the source files to the docker containers.
     *
     * @return void
     */
    public function deploy()
    {
        foreach ($this->containers as $container) {
            $this->taskExec("docker cp src $container:/opt/appserver/webapps/example/")->run();
        }
    }

    /**
     * Synchronizes the containers when a file changes.
     *
     * @return void
     */
    public function watch()
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