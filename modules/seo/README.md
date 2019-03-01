SEO модуль для моделей и контроллеров
===============================
Позволяет настаивать SEO как для статичных страниц контроллеров так и динамических

Установка
------------

main.php
```
'modules' => [
    'seo'=>[
        'class'=>'reketaka\helps\modules\seo\Module'
    ]
]
```
Console
main.php
```
'controllerMap' => [
    'migrate' => [
        'migrationNamespaces' => [
            'reketaka\helps\modules\seo\migrations'
        ]
    ]
]
```

В нужной модели добавляем поведение обязательно и именем **seo**
```
public function behaviors()
    {
        return [
            'seo'=>[
                'class'=>'reketaka\helps\modules\seo\behaviors\SeoFields',
            ]
        ]
    }
```

В view файле где редактируете вашу модель
```
    \reketaka\helps\modules\seo\widgets\SeoForm::widget([
        'form'=>$form,
        'model'=>$model
    ]);
```