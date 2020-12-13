## ТЗ PHP Laravel Developer
Создать классическое CRUD приложение.

1)Вы можете спокойно использовать как API, так и прямой Back-end от Laravel.

2)Использовать можно Vue, Bootstrap, VanillaJS.

<hr>

### Приложение для загрузки изображений

Стек:
* [Laravel] - v 8.15.0
* [Bootstrap] - v 5.0.0 beta 1
* [VanillaJS]

#### Установка

Распаковать содержимое папки **gallery** в корневую директорию проекта.

Выполнить
```sh
cp .env.example .env
```

Изменить переменные в файле .env в соответствии с вашими настройками DB
```sh
DB_DATABASE=имя базы данных
DB_USERNAME=пользователь
DB_PASSWORD=пароль
```

Выполнить

```sh
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
```

[Laravel]: <https://laravel.com/>
[Bootstrap]: <https://getbootstrap.com/>