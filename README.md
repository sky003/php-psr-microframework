# The simple PSR compatible PHP microframework

You probably shouldn't do this in the real life (except on some really spacial occasions) because you'll feel all the 
pain of an integration process of different PHP libraries to your project.  

## Requirements

* Docker (Docker Engine 1.13.1+)
* Docker Compose

## Installation

Execute to make an application runnable:
```sh
$ docker-compose up -d
$ docker-compose exec php-fpm bash   # open container's bash
$ composer install                   # install application dependencies
$ bin/console orm:schema-tool:create # make database in sync with current entity metadata (migrations not implemented)
```

## Running

Execute to run Docker based environment:
```sh
$ docker-compose up -d
```
Now REST API available on `http://127.0.0.1:8080`. See OpenAPI definition (`openapi.yml` file) for more details. 

Execute in case you need to **get authentication credentials** (currently it's valid JWT): 
```sh
$ docker-compose exec php-fpm bash -c "bin/console app:jwt:generate"
```
This command will return the valid JWT, like in the example below:
```sh
$ docker-compose exec php-fpm bash -c "bin/console app:jwt:generate"
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJqd3RfNWI3ZmZmMmE2MjhjMTIuNDEyNjczMDEifQ.3a4SZjrm3PsnY3Y2CX0nHdyIdClapR8giMGCGAv64Ng
``` 

## Tests

Execute to run the tests:
```sh
$ docker-compose exec php-fpm bash -c "vendor/bin/codecept run"
```