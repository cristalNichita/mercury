# переходим в папку проекта
cd projects/mercury/www

# обновляем из git - ветка develop
git reset --hard
git pull origin develop

# выходим если не смогли обновится
if [[ $? -ne 0 ]] ; then
  exit 1;
fi

# устанавливаем настройки окружения
cp .env.develop .env

# собираем vendor
composer install

# накатываем миграции laravel
php artisan migrate

# собираем front
yarn install
yarn dev

# Очистка лары
php artisan optimize:clear

## собираем документацию
#php artisan l5-swagger:generate
