Дефолтное меню с обычным параметром функций и методов
```php

$d = [
            [
                'label'=>'Настройки системы',
                'alias'=>'settings',
                'items'=>[
                    [
                        'label'=>'Права доступа',
                        'url'=>['/rbac'],
                        'alias'=>'settings_rbac',
                    ],
                    [
                        'label'=>'Логи',
                        'alias'=>'settings_logs',
                        'url'=>['/logreader/index']
                    ],
                    [
                        'label'=>'Файловый менеджер',
                        'url'=>['/filemanager/index'],
                        'alias'=>'settings_filemanager',
                    ],
                    [
                        'label'=>'Найстройки',
                        'url'=>['/settings'],
                        'alias'=>'settings_setting',
                    ],
                    [
                        'label'=>'Монитор заданий',
                        'url'=>['/monitor'],
                        'alias'=>'settings_monitor',
                    ],
                    [
                        'label'=>'Supervisord',
                        'url'=>['/supervisor'],
                        'alias'=>'settings_supervisor',
                    ]
                ]
            ],
            [
                'label'=>'Пользователи',
                'alias'=>'user',
                'items'=>[
                    [
                        'label'=>'Пользователи',
                        'alias'=>'user_users',
                        'url'=>['/usermanager/user/index'],
                    ],
                    [
                        'label'=>'Группы',
                        'alias'=>'user_groups',
                        'url'=>['/usermanager/user-group/index'],
                    ],
                ]
            ],
            [
                'label'=>'Системное меню',
                'alias'=>'system_menu',
                'items'=>[
                    [
                        'label'=>'Секции меню',
                        'alias'=>'menu_section',
                        'url'=>['/adminmenu/menu-section/index'],
                    ],
                    [
                        'label'=>'Элементы меню',
                        'alias'=>'menu_item',
                        'url'=>['/adminmenu/menu-item/index'],
                    ],
                    [
                        'label'=>'Права доступа к элементам меню',
                        'alias'=>'menu_permissions',
                        'url'=>['/adminmenu/menu-item-roles/index'],
                    ],
                    [
                        'label' => 'Секции пользователей',
                        'alias'=>'menu_section_user',
                        'url' => ['/adminmenu/menu-section-user/index'],
                    ],
                    [
                        'label' => 'Элементы секций пользователей',
                        'alias'=>'menu_item_section_user',
                        'url' => ['/adminmenu/menu-item-user/index'],
                    ],
                ]
            ]
        ];

        \reketaka\helps\modules\adminMenu\models\MenuMigrateCreatorHelper::updateOrCreate($d);
```