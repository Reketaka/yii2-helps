<?php


namespace reketaka\helps\common\controllers;

use reketaka\helps\modules\adminMenu\models\MenuItem;
use reketaka\helps\modules\adminMenu\models\MenuItemRoles;
use reketaka\helps\modules\adminMenu\models\MenuSection;
use yii\db\Exception;
use yii\helpers\Console;
use function array_key_exists;
use common\models\BaseHelper;
use DateTime;
use function implode;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\dictionaries\models\DictionariesHelper;
use reketaka\helps\modules\dictionaries\models\DictionariesName;
use Yii;
use yii\db\ColumnSchemaBuilder;
use yii\db\Query;
use yii\db\Schema;

class Migration extends \yii\db\Migration{

    public function addColumn($table, $column, $type)
    {
        $time = $this->beginCommand("add column $column $type to table $table");
        $this->db->createCommand()->addColumn($table, $column, $type)->execute();
        if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
            $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
        }

        if(method_exists($type, "isIndex") && $type->isIndex()){
            $name = "idx-$table-$column";
            $unique = $type->getUniqIndex();

            $time = $this->beginCommand('create' . ($unique ? ' unique' : '') . " index $name on $table (" . $column . ')');

            $this->db->createCommand()->createIndex($name, $table, $column, $unique)->execute();
            $this->endCommand($time);
        }


        $this->endCommand($time);
    }

    public function createTableFromSelect($tableName, Query $query){
        $this->execute("CREATE TABLE $tableName ".$query->createCommand()->getRawSql());
        return true;
    }

    public function insert($table, $columns)
    {
        $time = $this->beginCommand("insert into $table");
        $this->db->createCommand()->insert($table, $columns)->execute();
        $this->endCommand($time);

        return $this->getLastId();
    }

    public function dropView($name){
        $this->execute("DROP VIEW $name");
        return true;
    }

    public function getLastId(){
        return $this->db->getLastInsertID();
    }

    public function getCurrentDateTime(){
        return (new DateTime())->format("Y-m-d H:i:s");
    }

//    CREATE ALGORITHM = MERGE VIEW city2  AS SELECT * FROM tableName;
    public function createView($name, Query $query, $viewColumns = [], $algoritm = "MERGE"){
        $sql = [];
        $sql[] = "CREATE";
        if($algoritm){
            $sql[] = "ALGORITHM = $algoritm";
        }

        $sql[] = "VIEW $name";
        if($viewColumns){
            $sql[] = "(".implode(", ", $viewColumns).")";
        }

        $sql[] = "AS";
        $sql[] = $query->createCommand()->getRawSql();

        $sql = implode(' ' , $sql);


        $this->execute($sql);
        return true;
    }

    public function createTable($table, $columns, $options = null)
    {
        if($this->db->driverName == 'mysql' && !$options){
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $time = $this->beginCommand("create table $table");

        
        $this->db->createCommand()->createTable($table, $columns, $options)->execute();
        echo PHP_EOL;
        foreach ($columns as $column => $type) {
            if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
                $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
            }

            if(method_exists($type, "isIndex") && $type->isIndex()){
                $name = "idx-$table-$column";
                $unique = $type->getUniqIndex();

                $time = $this->beginCommand('create' . ($unique ? ' unique' : '') . " index $name on $table (" . $column . ')');
                $this->db->createCommand()->createIndex($name, $table, $column, $unique)->execute();
                $this->endCommand($time);
            }
        }


        $this->endCommand($time);
    }

    public function string($length = null)
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder(Schema::TYPE_STRING, $length)->null();
    }

    /**
     * @param $tableName
     * @param $keyName
     * @throws \yii\db\Exception
     * @return bool;
     */
    function isForeignKeyExists($tableName, $keyName)
    {
        $cnt = (new Query())
            ->createCommand()->setRawSql("
                SELECT COUNT(*) cnt
                FROM information_schema.table_constraints
                WHERE constraint_name = '{$keyName}'
                  AND table_name = '{$tableName}'")
            ->queryOne()['cnt'];
        return ($cnt != 0);
    }

    public function hasColumn($table, $columnName){
        $query = Yii::$app->db->createCommand("SHOW COLUMNS FROM `$table` LIKE '$columnName'")->queryOne();

        if(!array_key_exists('Field', $query)){
            return false;
        }

        return true;
    }

    /**
     * Создает справочник
     * @param $alias
     * @param $title
     * @param $values
     * @return bool
     */
    public function createDictionary($alias, $title, $values){
        $dictionary = DictionariesHelper::create($alias, $values, $title, true);

        return $dictionary;
    }

    /**
     * Удаляет справочник
     * @param $alias
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteDictionary($alias){
        if(!$dictionary = DictionariesName::findOne(['alias'=>$alias])){
            return true;
        }

        $dictionary->delete();

        return true;
    }

    /**
     * Удаляет раздел меню по alias
     * @param $alias
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteMenuSection($alias){
        if(!$menuSection = MenuSection::findOne(['alias'=>$alias])){
            return true;
        }

        $menuSection->delete();

        return true;
    }

    public function deleteMenuItem($sectionAlias, $itemAlias){
        if(!$menuSection = MenuSection::findOne(['alias'=>$sectionAlias])){
            throw new Exception("Раздел меню не найден");
        }

        $itemAlias = $sectionAlias."-".$itemAlias;

        if(!$menuItem = MenuItem::findOne(['alias'=>$itemAlias])){
            throw new Exception("Элемент меню не найден");
        }

        $menuItem->delete();

        echo Console::ansiFormat("--- Элемент меню {$menuItem->alias} удален", [Console::FG_GREEN]).PHP_EOL;

        return true;
    }

    public function deleteMenuItemAllRoles($sectionAlias, $menuItemAlias){
        if(!$menuSection = MenuSection::findOne(['alias'=>$sectionAlias])){
            throw new Exception("Раздел меню не найден");
        }

        $itemAlias = $sectionAlias."-".$menuItemAlias;

        if(!$menuItem = MenuItem::findOne(['alias'=>$itemAlias])){
            throw new Exception("Элемент меню не найден");
        }

        MenuItemRoles::deleteAll(['menu_item_id'=>$menuItem->id]);

        echo Console::ansiFormat("--- Все роли элемента меню {$menuItem->alias} удалены", [Console::FG_GREEN]).PHP_EOL;

        return true;

    }

    public function deleteMenuItemRoles($sectionAlias, $menuItemAlias, $roles = []){
        if(!$menuSection = MenuSection::findOne(['alias'=>$sectionAlias])){
            throw new Exception("Раздел меню не найден");
        }

        $itemAlias = $sectionAlias."-".$menuItemAlias;

        if(!$menuItem = MenuItem::findOne(['alias'=>$itemAlias])){
            throw new Exception("Элемент меню не найден");
        }

        foreach($roles as $roleName){
            MenuItemRoles::deleteAll(['role_name'=>$roleName]);

            echo Console::ansiFormat("--- Роль {$roleName} элемента меню {$menuItem->alias} удалена", [Console::FG_GREEN]).PHP_EOL;
        }
        return true;
    }

    /**
     * @param $alias
     * @param $label
     * @param bool $icon
     */
    public function createMenuSection($alias, $label, $icon = false){

        if(!$menuSection = MenuSection::findOne(['alias'=>$alias])){
            $menuSection = new MenuSection([
                'alias'=>$alias,
            ]);
        }

        $menuSection->title = $label;
        if($icon){
            $menuSection->icon = $icon;
        }

        $menuSection->save();

        echo Console::ansiFormat("*** Раздел меню {$menuSection->title} создан/обновлен", [Console::FG_GREEN]).PHP_EOL;

        return true;
    }

    /**
     * @param $sectionAlias
     * @param $postItemAlias
     * @param $label
     * @param $url
     * @param $controllerUniqueId
     */
    public function createMenuItem($sectionAlias, $postItemAlias, $label, $url, $controllerUniqueId, $roles = [], $icon = false){

        if(!$menuSection = MenuSection::findOne(['alias'=>$sectionAlias])){
            throw new Exception("Раздел меню не найден");
        }

        $itemAlias = $sectionAlias."-".$postItemAlias;

        if(!$menuItem = MenuItem::findOne(['alias'=>$itemAlias])){
            $menuItem = new MenuItem([
                'alias'=>$itemAlias
            ]);
        }

        $menuItem->title = $label;
        $menuItem->url = $url[0];
        if($icon){
            $menuItem->icon = $icon;
        }
        $menuItem->controller_uniq_id = $controllerUniqueId;
        $menuItem->section_id = $menuSection->id;
        $menuItem->save();

        echo Console::ansiFormat("--- Элемент меню {$menuItem->alias} создан/обновлен, раздел {$menuSection->alias}, id: {$menuItem->id}", [Console::FG_GREEN]).PHP_EOL;

        foreach($roles as $roleName){
            if(!MenuItemRoles::findOne([
                'role_name'=>$roleName,
                'menu_item_id'=>$menuItem->id
            ])){
                $itemRole = new MenuItemRoles([
                    'role_name'=>$roleName,
                    'menu_item_id'=>$menuItem->id
                ]);
            }

            echo Console::ansiFormat("### Добавлена роль {$roleName} для пункта меню {$menuItem->alias}", [Console::FG_GREEN]).PHP_EOL;

            $itemRole->save();
        }

        return true;
    }
}