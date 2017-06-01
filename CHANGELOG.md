# Version 2.2.4

## Bugfixes

* None

## Features

* Add LDAP example for a docker-compose setup
* Add prePassivate + postActivate methods to ASingletonProcessor for demonstration
* Switch to latest appserver-io-psr/epb version
* Add classes directories to composer autoloader

# Version 2.2.3

## Bugfixes

* Fixed invalid Platform class in appserver-ds.xml docker-compose configuration

## Features

* None

# Version 2.2.2

## Bugfixes

* Add missing DNS server IP in webserver.xml when starting example application with docker-compose

## Features

* None

# Version 2.2.1

## Bugfixes

* None

## Features

* Extend provisioning to also create a new database when using a MySQL

# Version 2.2.0

## Bugfixes

* None

## Features

* Switch to appserver.io version 1.1.3
* Removed Composer dependency to Rout.Lt, because 1.1.3 comes bundled with latest Rout.Lt version
* Add classes to reproduce issue [#881](https://github.com/appserver-io/appserver/issues/881)
* Refactor shopping cart action to use latest Rout.Lt 2.2.0+ features

# Version 2.1.17

## Bugfixes

* None

## Features

* Add OPCache GUI as DHTML servlet

# Version 2.1.16

## Bugfixes

* Fixed invalid usage of EM when startup SSB

## Features

* None

# Version 2.1.15

## Bugfixes

* None

## Features

* Add entity manager to SSB to test proxy integration

# Version 2.1.14

## Bugfixes

* Remove unnecessary module implementation
* Refactoring processor hierarchy

## Features

* Remove demo container implementation

# Version 2.1.13

## Bugfixes

* None

## Features

* Remove demo container implementation

# Version 2.1.12

## Bugfixes

* None

## Features

* Update to latest appserver.io Docker version

# Version 2.1.11

## Bugfixes

* None

## Features

* Update to latest appserver.io Docker version

# Version 2.1.10

## Bugfixes

* Bugfix for missing parameter in debug message of wait for DB loop

## Features

* None

# Version 2.1.9

## Bugfixes

* None

## Features

* Update to appserver.io Docker version 1.1.1-alpha10

# Version 2.1.8

## Bugfixes

* None

## Features

* Update to appserver.io Docker version 1.1.1-alpha9

# Version 2.1.7

## Bugfixes

* None

## Features

* Refactoring Docker integration + switch to DI for MQ senders

# Version 2.1.6

## Bugfixes

* None

## Features

* Replace hardcoded container name in login module's datasource lookup with variable

# Version 2.1.5

## Bugfixes

* None

## Features

* Use configuration variables introduces with appserver v1.1.1-alpha5 in context.xml/containers.xml file

# Version 2.1.4

## Bugfixes

* None

## Features

* Add application specific system logger to context.xml file

# Version 2.1.3

## Bugfixes

* None

## Features

* Add preAttach() and preDestroy() methods to close database connection

# Version 2.1.2

## Bugfixes

* Switch back to sqlite database

## Features

* None

# Version 2.1.1

## Bugfixes

* None

## Features

* Refactoring for new security subsystem

# Version 2.1.0

## Bugfixes

* None

## Features

* Refactoring for new security subsystem

# Version 2.0.6

## Bugfixes

* Fixed invalid pointcut order to enable action validation

## Features

* Securing Web Application

# Version 2.0.5

## Bugfixes

* Fixed invalid lookup name for QueueSender in AnnotatedServlet

## Features

* Add dummy Persistence Unit configuration to deployment descriptor

# Version 2.0.4

## Bugfixes

* Refactoring Entity folder structure to have AbstractEntity in a separater folder to avoid fatal error in Doctrine class loading

## Features

* Add JMeter testsuite for testing session persistence

# Version 2.0.3

## Bugfixes

* None

## Features

* Add more default credentials and query username after successfull login in JMeter tests

# Version 2.0.2

## Bugfixes

* None

## Features

* Fixed cart functionality

# Version 2.0.1

## Bugfixes

* None

## Features

* Fixed invalid add to cart functionality

# Version 2.0.0

## Bugfixes

* None

## Features

* Switch to Rout.Lt 2

# Version 1.2.1

## Bugfixes

* Fix a problem with colliding authentication paths

## Features

* None

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
