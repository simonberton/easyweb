# Easy Web Admin Symfony 4.3

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

It will create the following so we can add, delete and modify in our cms:
- Entity
- Form
- Service
- Repository
- Controller



# Infrastructure

## Requirements

You'll need the following software installed on your machine:

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [PHP-cs-fixer](http://cs.sensiolabs.org/)

## Docker

Create docker-compose environment file

```bash
cp infra/docker/local/dist.env infra/docker/local/.env
```

Change the environment variables if needed. Defaults may suit your needs.

Build the containers and start them

```bash
bin/docker build
```

## Database

```bash
docker exec -t easy-dbserver mysql -e "CREATE DATABASE IF NOT EXISTS easy"
docker exec -t easy-dbserver mysql -e "GRANT ALL ON easy.* TO 'easy'@'%' IDENTIFIED BY 'easy'"
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
### Node dependencies
```bash
yarn install
```
### Assets

Build assets
```bash
yarn encode dev
```


## Host and CMS

Update your /etc/host file:

```bash
echo "127.0.0.1 easy.loc" >> /etc/hosts
```

Open a browser and go to:

* http://easy.loc/admin


## Git Hooks

Set pre-commit hook.

```bash
cp infra/git/hooks/pre-commit .git/hooks/pre-commit
chmod a+x .git/hooks/pre-commit
```



