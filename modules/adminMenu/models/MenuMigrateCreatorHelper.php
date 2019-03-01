<?php

namespace reketaka\helps\modules\adminMenu\models;


use yii\base\Model;

class MenuMigrateCreatorHelper extends Model{

    /**
     * $d = [
     *      [
     *          'label'=>'Лиды',
     *          'alias'=>'lead',
     *          'items'=>[
     *              [
     *                  'label'=>'История авторизации телефона в лк',
     *                  'url'=>['/history-authorized-lk/index'],
     *                  'roles' => ['viewStatisticLead']
     *              ],
     *          ]
     *      ],
     *   ];
     * @param $d
     */
    public static function updateOrCreate($d){

        foreach($d as $sectionData){

            if(!$section = MenuSection::findOne([
                'alias'=>$sectionData['alias']
            ])){
                $section = new MenuSection([
                    'title'=>$sectionData['label'],
                    'alias'=>$sectionData['alias']
                ]);
                $section->save();
            }

            foreach($sectionData['items'] as $menuItemData){

                if(!$menuItem = MenuItem::findOne([
                    'url'=>$menuItemData['url'][0],
                    'section_id'=>$section->id
                ])){
                    $menuItem = new MenuItem([
                        'title'=>$menuItemData['label'],
                        'url'=>$menuItemData['url'][0],
                        'section_id'=>$section->id
                    ]);
                    $menuItem->save();
                }

                if(array_key_exists('roles', $menuItemData)){
                    foreach($menuItemData['roles'] as $roleName){
                        if(!MenuItemRoles::findOne([
                            'role_name'=>$roleName,
                            'menu_item_id'=>$menuItem->id
                        ])){
                            $itemRole = new MenuItemRoles([
                                'role_name'=>$roleName,
                                'menu_item_id'=>$menuItem->id
                            ]);
                            $itemRole->save();
                        }
                    }
                }
            }

        }

    }

}