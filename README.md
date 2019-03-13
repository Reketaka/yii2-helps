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

Модель Regedit

**console config.php**
```
'controllerMap' => [
        'regedit'=>[
            'class'=>'reketaka\helps\common\commands\RegeditController'
        ]
    ],
```

**config.php**
```
    'components' => [
        'regedit'=>[
            'class'=>'reketaka\helps\common\models\Regedit'
        ]
    ]
```

**Использование**
```
    Yii::$app->regedit->get('//modules/myModule/someVar');
    Yii::$app->regedit->remove('//modules/myModule/someVar');
    Yii::$app->regedit->clearCache();
    Yii::$app->regedit->getList('//modules/myModule/someVar');
    Yii::$app->regedit->set('//modules/myModule/someVar', 'test');
    
```

**Модуль обмена данными с 1c**

Для установки модуля принятия данных с 1с
модуль автоматически принимает сохраняет данные, возможно принятия данных в zip формате

Все принятые файлы храняться в `$module->getProgressDirPath()` после их обраотки можете скопировать их в папку `$module->getBackupDirPath()`

Вызов в 1с
```
http://dev_price.sunrise22.ru/importOnec/import-onec/auto
```

```
'modules'=>[
        'importOnec'=>[
            'class'=>'reketaka\helps\modules\onec\Module',
            'userName'=>'test';
            'userPassword'=>'test';
            'authKeyName'=>'AuthKey';
            'authKeyVal'=>'pzshkmm0VzIZru65cB1Zsr6o47xZYqpR';
            'maxFileSize'=>102400;
            'enableZip'=>true;
            'saveDirPath'=>'@backend/runtime/temp';
            'authKeyCallback' => function(){
                if (!($cookie = Yii::$app->request->headers->get('cookie', false))) {
                    throw new Exception('Not find authKey in Cookie');
                }

                list($n, $authKey) = explode('=', $cookie);
                return $authKey;
            }
        ]
    ]
```