# Список студентов

Переписанное на компонентах Symfony приложение по регистрации абитуриентов. [Ссылка на старый проект](https://github.com/Bocmah/student-list).

## Требования к установке

* PHP 7.1+
* Composer
* Apache с настроенным DocumentRoot на папку /public (пример настройки ниже

## Использованные компоненты Symfony

* HttpKernel
* HttpFoundation
* Routing
* EventDispatcher
* DependencyInjection

## Установка

Перейдите в папку, в которой хотите поместить проект, и клонируйте репозиторий:

```sh
$ git clone https://github.com/Bocmah/student-list.git
```

Выделите виртуальный хост под этот проект и настройте его следующим образом:

```apacheconf
<VirtualHost *:80>
    DocumentRoot "/path/to/student-list/public"
    ServerName students.loc
    
  <Directory /path/to/student-list/public>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
  </Directory>
</VirtualHost>
```

Перейдите в папку проекта и выполните:

```sh
$ composer install
```

Создайте базу данных и импортируйте SQL дамп, находящийся в корне проекта.

Скопируйте содержимое `config.php.example` в новый файл `config.php`:

```sh
$ cp config.php.example config.php
```

Измените параметры в конфиге так, чтобы они соответствовали параметрам созданной вами базы данных.

## Функциональность проекта

* Регистрация нового абитуриента
* Зарегистрированный абитуриент может просматривать свой профиль и редактировать информацию о себе
* Просмотр списка всех зарегистрированных абитуриентов
* Сортировка по любому столбцу списка
* Поиск по любому столбцу списка

## Скриншоты
![image](https://user-images.githubusercontent.com/32432647/46250191-56ea1d00-c43e-11e8-8411-52f603c8d773.png)




