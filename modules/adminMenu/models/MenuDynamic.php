<?php

namespace reketaka\helps\modules\adminMenu\models;

use common\helpers\BaseHelper;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MenuDynamic{

    CONST CACHE_KEY = 'defaultMenuItemsUser';
    CONST CACHE_KEY_ALL = 'all';

    /**
     * Очищает кеш меню для определенного пользователя
     * @param User $user
     */
    public static function clearCacheMenuForUser($userId){
        \Yii::$app->cache->delete(self::CACHE_KEY.$userId);
        \Yii::$app->cache->delete(self::CACHE_KEY.$userId.self::CACHE_KEY_ALL);
    }

    public static function clearCacheMenuAll(){
        \Yii::$app->cache->delete(self::CACHE_KEY.self::CACHE_KEY_ALL);
    }

    public function generate($generateAll = false){
        if(\Yii::$app->user->isGuest){
            return [];
        }

        $cache = \Yii::$app->cache;
        $cacheKey = self::CACHE_KEY . \Yii::$app->user->getId().($generateAll?self::CACHE_KEY_ALL:null);

        if ($d = $cache->get($cacheKey)) {
            return $d;
        }

        $sections = null;
        if(!$generateAll) {
            $userHasOwnMenu = false;
            $sections = MenuSectionUser::find()->where(['user_id' => \Yii::$app->user->getId()])->orderBy(['order' => SORT_ASC])->all();

            if ($sections) {
                $userHasOwnMenu = true;
            }
        }


        if(!$sections) {
            $sections = MenuSection::find()->orderBy(['order' => SORT_ASC])->all();
        }

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

    public function getItemsSection($section, $userRoles){

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
                'url'=>Url::to($item->url),
                'id'=>$item->id
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

        $superadminRole = \Yii::$app->getModule('adminmenu')->superAdminRole;

        if(in_array($superadminRole, $userRoles)){
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