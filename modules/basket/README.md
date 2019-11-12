Установка
===
в basketItemFields указываются поля которые нужно сохранить элементу корзины, беруться из модели продукта, модель продукта должна наследовать интерфейс reketaka\helps\modules\basket\interfaces\IBasketProduct


**config.php**
```
'modules'=>[
        'basket'=>[
            'class'=>'reketaka\helps\modules\basket\Module',
            'basketItemFields'=>['original_number', 'custom_field']
        ]
],
'components'=>[
    'basket'=>[
        'class'=>'reketaka\helps\modules\basket\models\BasketComponent'
    ]
],
'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'useTablePrefix' => false,
            'templateFile' => '@reketaka/helps/common/views/migration/migration.php',
            'generatorTemplateFiles' => [
                'create_table'=>'@reketaka/helps/common/views/migration/createTableMigration.php'
            ],
            'migrationNamespaces' => [
                'reketaka\helps\modules\basket\migrations',
            ]
        ],
    ]
```



``` 
php yii migrate
```