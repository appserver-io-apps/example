<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

use Lurker\Event\FilesystemEvent;

class RoboFile extends \Robo\Tasks
{

    /**
     * Deploy's the source files to the docker containers.
     *
     * @return void
     */
    public function deploy()
    {
        $this->taskExec('docker cp src appdata:/opt/appserver/webapps/example/')->run();
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

                $relativePath = str_replace(__DIR__ . '/src/', '', $event->getResource());

                $this->taskExec(
                    sprintf(
                        'docker exec -t appdata mkdir -p /opt/appserver/webapps/example/%s',
                        dirname($relativePath)
                    )
                )->run();


                $this->taskExec(
                    sprintf(
                        'docker cp %s appdata:/opt/appserver/webapps/example/%s',
                        $event->getResource(),
                        $relativePath
                    )
                )->run();

            }

        )->run();
    }
}