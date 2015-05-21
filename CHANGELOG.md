# Version 1.2.0

## Bugfixes

* None

## Features

* Switch to new Doctrine integration

# Version 1.1.1

## Bugfixes

* None

## Features

* Merge with features from 1.0 branch

# Version 1.1.0

## Bugfixes

* None

## Features

* Switch to new appserver.io v1.1.0 provisioning functionality
* Switch to latest components/bootstrap version 3.3.4

# Version 1.0.3

## Bugfixes

* Fix to close Doctrine connections when synchronizing SFSB + SLSB
* Fixed [#39](https://github.com/appserver-io-apps/example/issues/39) - MySQL not working as datasource

## Features

* None

# Version 1.0.2

## Bugfixes

* None

## Features

* Remove WebSocket example to improve performance on Windows, because socket check is very slow

# Version 1.0.1

## Bugfixes

* None

## Features

* Remove WebSocket example because WebSocket server components are not part of 1.0.0 standard distribution

# Version 1.0.0

## Bugfixes

* None

## Features

* Switched to stable dependencies due to version 1.0.0 release

# Version 0.12.6

## Bugfixes

* None

## Features

* Extend example for SSB with counter
* Add example for logger injection

# Version 0.12.5

## Bugfixes

* None

## Features

* Upgrade after moving InitialContext to appserver-io-psr/naming package

# Version 0.12.4

## Bugfixes

* None

## Features

* Upgraded AOP usage to the new appserver-io-psr/mop dependency

# Version 0.12.3

## Bugfixes

* Removed a forgotten error_log() from \AppserverIo\Apps\Example\MessageBeans\ImportChunkReceiver class

## Features

* None

# Version 0.12.2

## Bugfixes

* Switch to latest appserver-io/doppelgaenger 0.5.* version

## Features

* None

# Version 0.12.1

## Bugfixes

* Wrong usage of the TimerServiceContext interface

## Features

* None

# Version 0.12.0

## Bugfixes

* Some minor bugfixes

## Features

* Refactoring for usage with latest interface naming conventions of appserver-io/appserver 1.0.0-rc1
* Updating DocBlocks for phpDocumentor 2 standard
* Updated dependencies

# Version 0.11.2

## Bugfixes

* Fixed DbC errors

## Features

* None

# Version 0.11.1

## Bugfixes

* Fixed appserver-io/appserver#401

## Features

* None

# Version 0.11.0

## Bugfixes

* None

## Features

* Refactoring for usage with 1.0.0-rc1

# Version 0.10.11

## Bugfixes

* Re-add phpinfo.php because of JMeter PHP-FPM tests

## Features

* None

# Version 0.10.10

## Bugfixes

* None

## Features

* Updated configuration files to reflect latest changes in validation and configuration

# Version 0.10.9

## Bugfixes

* None

## Features

* Introduced the new base modifier for virtual host independent base URL generation

# Version 0.10.8

## Bugfixes

* None

## Features

* Customize epb.xml for usage with 1.0.0-beta4
* Add META-INF/ebp.xml for show how Session/MessageDriven Beans can be configured by XML configuration

# Version 0.10.7

## Bugfixes

* None

## Features

* Bugfix for example with interval timer

# Version 0.10.6

## Bugfixes

* None

## Features

* Refactoring for new annotated MQ example

# Version 0.10.5

## Bugfixes

* None

## Features

* Added a pointcuts.xml as an example for AOP configuration over XML

# Version 0.10.4

## Bugfixes

* None

## Features

* Add AnnotatedServlet as example for annotation based servlet configuration

# Version 0.10.3

## Bugfixes

* Add error message if try to store user, because action ist not yet implemented

## Features

* Hide web socket example if web socket server is not available

# Version 0.10.2

## Bugfixes

* None

## Features

* Add servlets to simulate requests with a random runtime + dummy SOAP server/client implementation
* Refactor JMeter testcases to allow protocol definition by variable

# Version 0.10.1

## Bugfixes

* Cleanup of old use statements

## Features

* Introduced an example of aspect based weaving with AppserverIo\Apps\Example\Aspects\LoggerAspect

# Version 0.10.0

## Bugfixes

* None

## Features

* Moved to the appserver-io-apps organisation
