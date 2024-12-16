## Установка для локальной разработки
- Скопировать .env.example  в .env и указать в APP_DIR название директории проекта, остальные настройки опциональны
- Запустить сборку проекта - `make build`
- Запустить первичную установку пакетов командой `make install`
- Запустить инициализацию npm `make npm`
- Запустить обновление структуры базы данных `make migrate`
- Запустить первичное наполнение базы данных `make seed`

Опционально: 
Чтобы наполнить базу стартовыми сущностями
```
make bash
php artisan db:seed --class=LocalSeeder 
```

## Работа с переводами
```
make bash
php artisan lang:convert to my_filename.xlsx
php artisan lang:convert from my_filename.xlsx
```

Переводы сохраняются в storage

## Полезности
Полный список полезных команд в файле Makefile

Пересборка проекта
```
make build
```

Остановка проекта
```
make down
```

Список запущенных контейнеров
```
make ps
```

Терминал PHP контейнера
```
make bash
```

php artisan db:seed
```
make seed
```

php artisan migrate
```
make migrate
```

php artisan migrate:refresh
```
make migrate-refresh
```

composer update
```
make composer-update
```

composer test
```
make composer-test
```

composer install migrate
```
make local-update
```

php artisan migrate:refresh --seed
```
make refresh
```

Проверка стиля кода
```
make pint-diff
```

Иправление стиля
```
make pint
```

php artisan tinker
```
make tinker
```
