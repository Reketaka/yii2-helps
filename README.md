Пакет полезных хелповых функций
===============================
Набор полезных хелповых функций, виджетов и модулей

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist reketaka/yii2-helps "*"
```

or add

```
"reketaka/yii2-helps": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Установка контроллера миграций, задает ```CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB``` если не указанны опции создания таблицы

```php
'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'templateFile' => '@reketaka/helps/common/views/migration/migration.php',
            'generatorTemplateFiles' => [
                'create_table'=>'@reketaka/helps/common/views/migration/createTableMigration.php'
            ]
        ]
```