# Example Application

This is the example for a web application using Servlet-/PersistenceContainer and MessageQueue. The example application should give developers a broad overview of the possibilities they have as well as best practices.

## Running with Docker

The application comes with a common Dockerfile (All-in-One container) and a docker-compose.yml (Distributed application with a container for each service).

The simplest way to run the example using Docker is

```sh
$ docker run -p9080:80 appserver/example
```

This uses the last image found on [Docker Hub](https://hub.docker.com/r/appserver/example/) and starts a fresh container All-in-One container. To start using the application, open a browser and enter the URL [http://127.0.0.1:9080/example](http://127.0.0.1:9080/example).

The second way the start the application is `docker-compose` which starts a distributed version of the application, whereas each service

* Webserver
* Message-Queue
* Persistence-Container
* PHP-FPM
* MySQL

start's a own container. The containers are connected by the Docker network and the application components are using the remote interfaces to communicate with each other.