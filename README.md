# Marketplace

A marketplace system.

## Requirements

- An Environment for [Lumen 8.x](https://lumen.laravel.com/docs/8.x/installation)
- Composer
- [Xdebug](https://xdebug.org/) for tests coverage

If you use [docker](https://www.docker.com/), here you will find a complete environment container.

## Installation

Clone or download this repository.

Copy `.env.example` to `.env`. After that, follow one of next option. Don't forget set the APP_TIMEZONE in `.env`.

### Docker

At the root of the project there is a Dockerfile and docker-compose.yml files with requirements to run the project.
You can run with docker by building the image and running it.

Execute:

```bash
# Build/start container
docker-compose up -d

# Install dependencies
docker exec -ti marketplace_api composer install

# Execute migrate
docker exec -ti marketplace_api php artisan migrate:fresh --seed

# Generates jwt secret
docker exec -ti marketplace_api php artisan jwt:secret
```

You'll have API running on http://localhost

### Local

Add database configuration on `.env` file.

```bash
# Install packages
composer install

# Creates database and populating it
php artisan migrate --seed

# Set the JWTAuth secret key used to sign the tokens
php artisan jwt:secret

# Start application
php -S localhost:8000 -t public
```

You'll have API running on http://localhost:8000

## Usage

After starting application, you'll have access to following routes:


| Method | URI                              | Authentication | Payload                                      |
|:------:|----------------------------------|:--------------:|----------------------------------------------|
| GET    | /                                | No             |                                              |
| GET    | api/price-conversion/{amount}    | No             |                                              |
| POST   | api/auth/login                   | No             | 'email': string, 'password': string          |
| POST   | api/auth/me                      | Yes            |                                              |
| POST   | api/auth/refresh                 | Yes            |                                              |
| POST   | api/auth/logout                  | Yes            |                                              |
| GET    | api/product                      | No             |                                              |
| GET    | api/product/{id}                 | No             |                                              |
| GET    | api/product/{productId}/prices   | No             |                                              |
| POST   | api/product                      | Yes            | 'description': string, 'price': number       |

By default, an user is available for login, with these credentials:
```JSON
{
    "email": "admin@fake.mail",
    "password": "admin123"
}
```
**Warning:** User with public default login is a huge security issue. This project is still under construction (contributions are welcome :smiley:). 
Meantime, implement your own user control. In this project, you can find the UserController file to start this implementation and then eliminate the user seed.

Maybe you could think "Why creates this user?". It's for fast manual testing and visualization of features by authentication routes.

Application is already to use.

### Authentication

This API implements JWT authentication, so to consume resources protected by authentication, after logging in with your previously registered user, add Bearer authentication with the token received at login to your header.

## Tests

### Tests on docker

To run tests, execute inside of container application `composer test` in app directory. For example:

```bash
docker exec marketplace_api composer test
```

OR

```bash
# Execute bash of container
docker exec -ti marketplace_api bash

#execute tests
composer test
```

### Tests on local

To run tests run on root of the project:

```bash
composer test
```

### Coverage

Run `composer coverage` at the root of the project and then a `coverage` directory will be created in `test` folder.
Open `./test/coverage/index.html` to see tests coverage.

#### Docker

Run at root of the project:

```bash
docker exec marketplace_api composer coverage
```

OR

```bash
# Execute bash of container
docker exec -ti marketplace_api bash

#execute tests
composer coverage
```

#### Local

Run at root of the project:

```bash
composer coverage
```

## Documentation

In order to document the endpoints, you can use openapi schemas and see the result with [swagger](https://swagger.io/docs/specification/2-0/what-is-swagger/).
To generate documentation run `php artisan swagger:scan` at root of the project. 

A `swagger.json` file will be generated in public folder. Some IDEs allow visualization with an embedded server and opening `swagger-ui.html`.
Easy viewing will soon be added.

### Docker

Run at root of the project:

```bash
docker exec marketplace_api php artisan swagger:scan
```

OR

```bash
# Execute bash of container
docker exec -ti marketplace_api bash

#execute tests
php artisan swagger:scan
```

### Local

Run at root of the project:

```bash
php artisan swagger:scan
```

## Contribute

Soon I'll be set templates for contributes.

## TODO

- [ ] Improve swagger:scan command tests
- [ ] Improve swagger documentation
- [ ] Product images
- [ ] Implements user control
    + [ ] Register
    + [ ] Password recovery
    + [ ] Delete
- [ ] Paginate get products
- [ ] Product categories 
- [ ] Backoffice


## License

[MIT](./LICENSE)
