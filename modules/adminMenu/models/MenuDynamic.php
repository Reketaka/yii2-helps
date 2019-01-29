<?php

namespace reketaka\helps\modules\adminMenu\models;

use common\helpers\BaseHelper;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MenuDynamic{

    CONST CACHE_KEY = 'defaultMenuItemsUser';

    /**
     * Очищает кеш меню для определенного пользователя
     * @param User $user
     */
    public static function clearCacheMenuForUser($userId){
        \Yii::$app->cache->delete(self::CACHE_KEY.$userId);
    }

    public function generate(){
        if(\Yii::$app->user->isGuest){
            return [];
        }

        $cache = \Yii::$app->cache;
        $cacheKey = self::CACHE_KEY.\Yii::$app->user->getId();

        if($d = $cache->get($cacheKey)){
            return $d;
        }


        $sections = MenuSection::find()->orderBy(['order'=>SORT_ASC])->all();

        if(!$sections){
            return [];
        }

        $user = \Yii::$app->user->identity;
        $userRoles = $user->getRoles();

        $r = [];

        foreach($sections as $section){

            $t = [
                'label'=>$section->title,
                'icon'=>'align-left',
                'url'=>'#',
                'items'=>$this->getItemsSection($section, $userRoles)
            ];

            if(!$t['items']){
                continue;
            }

            $r[] = $t;
        }

        $cache->set($cacheKey, $r);

        return $r;
    }

    public function getItemsSection(MenuSection $section, $userRoles){

        $items = $section->getMenuItems()->orderBy(['order'=>SORT_ASC])->all();

        $r = [];
        if(!$items){
            return $r;
        }

        foreach($items as $item){
            /**
             * @var $item MenuItem
             */

            $t = [
                'label'=>$item->title,
                'url'=>Url::to($item->url)
            ];

            if(!$this->checkAccessToMenuItem($userRoles, $item->menuItemRoles)){
                continue;
            }


            $r[] = $t;
        }

        return $r;
    }

    public function checkAccessToMenuItem($userRoles, $menuRoles){

        $itemRoles = ArrayHelper::getColumn($menuRoles, 'role_name');

        if(in_array('superadmin', $userRoles)){
            return true;
        }

        if(!$itemRoles){
            return false;
        }

        if(!array_intersect($userRoles, $itemRoles)){
            return false;
        }

        return true;

    }


}