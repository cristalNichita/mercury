## Установка проекта

Скопировать .env.example в .env и заполнить данными подключения к базе данных

```
composer install

php artisan key:generate
php artisan migrate
php artisan storage:link

npm i
npm run watch
```
Перейти http://localhost:3000

### Заполнить демо данными
```
php artisan db:seed
php artisan module:seed
```
### Полезные ссылки

[Artisan команды](https://nwidart.com/laravel-modules/v6/advanced-tools/artisan-commands/) для модульной системы

### Модули системы

#### Settings - Настройки
```
Settings::get();
Settings::get('phone');
Settings::set('email', 'info@echo-company.ru');
```

## События

ContactCreated - создан новый контакт

CompanyCreated - создан новый контрагент
