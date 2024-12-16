## Установка для локальной разработки
- переименовать .env.example  в .env и проставить доступы в базу
- выполнить composer install
- выполнить npm i
- запустить php artisan migrate
- запустить php artisan storage:link
- запустить php artisan db:seed

Опционально: 
чтобы наполнить базу стартовыми сущностями
php artisan db:seed --class=LocalSeeder 


## Работа с переводами
php artisan lang:convert to my_filename.xlsx
php artisan lang:convert from my_filename.xlsx

Переводы сохраняются в storage
