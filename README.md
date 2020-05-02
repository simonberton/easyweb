# Easy Web Admin Symfony 5.0.2

Easy web admin comes by default with some basic entities:
- Contact
- Post
- Category
- Image

It's purpose is to be a CMS with very easy extensibility.

By Running this command:
```bash
    bin/console easy:create-admin-object myNewEntity
```

It will create:
- Entity
- Form
- Service
- Repository
- Controller

So we have all we need to create, edit and delete our newly created entity.
By default it will create a route on our /admin/myNewEntity

If we want to change it, just go to the controller: myNewEntityController.php and change the route.

# Infrastructure

## Requirements

You'll need the following software installed on your machine:

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [PHP-cs-fixer](http://cs.sensiolabs.org/)

```
## Docker

Create docker-compose environment file

```bash
cp infra/docker/dist.env infra/local/.env
```

Change the environment variables if needed. Defaults may suit your needs.

Build the containers and start them

```bash
docker-compose up --build -d
```

## Database

```bash
docker exec -t easy-dbserver mysql -e "CREATE DATABASE IF NOT EXISTS greta"
docker exec -t easy-dbserver mysql -e "GRANT ALL ON greta.* TO 'greta'@'%' IDENTIFIED BY 'greta'"
docker exec -t easy-dbserver mysql -e "FLUSH PRIVILEGES"
```

## App vendors

Copy Symfony env file (go back to root directory of the project)

```bash
cp dist.env .env
```

Install PHP vendors via composer.

```bash
docker exec -it easy-php composer install
```

Create DB schema

```bash
docker exec -it easy-php bin/console doctrine:schema:create
```

### Assets

Build assets
```bash
docker exec -it easy-php yarn encore dev
```

Load Fixtures
```bash
docker exec -it easy-php bin/console doctrine:fixtures:load
```

## Host and CMS

Update your /etc/host file:

```bash
echo "127.0.0.1 greta.loc" >> /etc/hosts
```

Open a browser and go to:

* http://greta.loc/admin
User: admin@test.com
Password: admin

## Git Hooks

Set pre-commit hook.

```bash
cp infra/git/hooks/pre-commit .git/hooks/pre-commit
chmod a+x .git/hooks/pre-commit
```



