# Fake Twitter Backend

## Technologies
```
1. Laravel v10.31.0
2. Vue v3.3.8
3. InertiaJS
4. Laravel Passport (For JWT Token)
5. Ziggy Router
6. Bootstrap
```

## Installation

``` 
1. git clone https://github.com/alamincse/fake-twitter-backend.git
2. make .env file with proper database connection
3. composer install
```


## Laravel Passport Install
```
1. composer require laravel/passport [If not please use this command `composer require laravel/passport --with-all-dependencies`]
2. php artisan migrate
3. php artisan passport:install
```

## Vue Install
```
1. composer require laravel/ui
2. npm install vue
3. npm install && npm run dev
```

## InertiaJS Install
```
1. composer require inertiajs/inertia-laravel
2. php artisan inertia:middleware (No need to run this command)
3. npm install @inertiajs/vue3
```

## Ziggy Router Install
```
1. composer require tightenco/ziggy
2. php artisan ziggy:generate [Please put your app url on your .env file, like: APP_URL=http://127.0.0.1:8000 or what will your after serve url then run this command]
```


## Serve
```
1. php artisan key:generate [If app does not have key in .env file]
2. php artisan serve
```
