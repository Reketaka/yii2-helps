Установка
===

Сначала подключаете модуль к системе, определеяет дополнительные поля таблицы с товарами

**config.php**
```
'catalog'=>[
            'class'=>'reketaka\helps\modules\catalog\Module',
            'tableItemFields'=>[
                'testField'=>function(Schema $schema){
                    return [
                        Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_STRING)->null()->asIndex()
                    ];
                }
            ]
        ]

'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'useTablePrefix' => false,
            'templateFile' => '@reketaka/helps/common/views/migration/migration.php',
            'generatorTemplateFiles' => [
                'create_table'=>'@reketaka/helps/common/views/migration/createTableMigration.php'
            ],
            'migrationNamespaces' => [
                'reketaka\helps\modules\catalog\migrations',
            ]
        ],
    ]
```

**config.php**
```
    'components' => [
        'db'=>[
            'schemaMap' => [
                'mysql'=>'reketaka\helps\common\models\db\mysql\Schema'
            ]
        ]
    ]
```

``` 
php yii migrate
```