# Платежный сервис

# Стек
  - Laravel Lumen (6.2.0)
  - MySQL (5.7.22)
  - Nginx (1.17.5)

## Установка
```sh
$ cd payment_service/
$ sudo docker-composer up -d
$ sudo docker exec app composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate
```
