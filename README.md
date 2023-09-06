# Kapi Tasks
This is a sample Laravel project to show service design pattern.

## Getting Started

Download the latest source
```
git clone https://github.com/kurianvarkey/kapi-laravel-tasks.git kapi-laravel-tasks
```

Please install docker if not installed and run 

```
docker-compose up -d
```

### Run the migration
```
docker-compose exec app php artisan migrate
```

### Run the test
```
docker-compose exec app php artisan test --coverage
```

### Application url
http://localhost:8000/1.0

http://localhost:8000/1.0/users

http://localhost:8000/1.0/tasks


## Postman collection
There is a postman collection available in the postman directory to test from postman

### Run the database seeding first if need run from postman
```
docker-compose exec app php artisan db:seed
```

## Docker Troubleshooting
In case docker is showing any issues or errors, please try following:

After downloading/cloning the kapi-laravel-tasks,

```
$ cd kapi-laravel-tasks
$ docker run --rm -v $(pwd):/app composer install
$ sudo chown -R dev:dev .
```