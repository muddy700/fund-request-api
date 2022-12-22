# FUND-REQUEST-API

This is the RESTful API repository for the
[iPF OS(Fund Request Module)](http://localhost:8000), which deals with **Fund Request Management** where **Staff** will be able to request money / fund from the **Finance Manager** so as to meet their daily activities (related to work like voucher for calling customers) or personal issues like loans and other activities.

## Contents

- [Requirements](#requirements)
- [Tools](#tools)
- [Installation](#installation)
- [Development](#development)
- [Test](#test)
- [Production](#production)

## Requirements

- Laravel >= `9.x`
- Apache >= `2.4.52`
- Mysql
- Php >= `8.1.6`

## Tools

- [Cors](https://www)
- [Passport](https://www)
- [Swagger](https://www.)
- [PHPUnit](https://www.)
- [Bugsnag](https://www.bugsnag.com/)

## Installation

- Clone this repository.

```sh
 git clone https://github.com/muddy700/fund-request-api.git fund-request-api
```

- Create .env

```sh
 cp .env.example .env
```

- Create a database

 ```sh
 DB_DATABASE=<Your Database Name>
 DB_USERNAME=<DB Username>
 DB_PASSWORD=<DB Password>
 ```

- Update dependencies

```sh
 composer update
```

- Generate Key

```sh
 php artisan key:generate
```

## Development

```sh
 php artisan migrate
 php artisan db:seed 
 php artisan serve
```

## Test

- Unit tests are done using **PHPUnit**.

    To run tests just execute the command below

```bash
 ./vendor/bin/phpunit --verbose tests
```

## Production

```sh
 git push staging <your-local-branch-with-changes>:master
```
