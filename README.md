# link_shortner

## Project setup

#### 1 - Copy the .env file
```cp .env.example .env```

#### 2 - Create a local database and configure it on the .env file

#### 3 - Install composer
```composer install```

#### 4 - Generate artisan key
```php artisan key:generate```

#### 5 - Run the migrations 
```php artisan migrate```

#### 5 - Install npm dependencies
```npm install```

## Run the project
### API (Laravel)
```php artisan serve```

### Frontend (Vue, scss)
```npm run watch```

## API

#### List the last 5 urls saved
```/api/urls```
 
#### List all saved urls
```/api/urls```

#### Redirect to the original url
```/{short_url}```

#### Store new Url
```/api/url/store```

#### Check if short url isn't already used
```/api/url/exists/{short_url}```
