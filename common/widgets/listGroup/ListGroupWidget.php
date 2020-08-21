<?php

namespace reketaka\helps\common\widgets\listGroup;

use yii\base\Widget;

/**
 * ```
 * 'options'=>[
 *      'primaryKeyTitle'=>'id',
 *      'primaryKey'=>'id',
 *      'titleKey'=>'telephone',
 *      'url'=>['/ogranization/organization-telephone/view']
 *      'titleClosure'=>function($model){
 *
 *      }
 * ]
 * ```
 * @var $options
 */
class ListGroupWidget extends Widget{

    public $title;
    public $options;
    public $items;

    public function run()
    {

        return $this->render('index', [
            'items'=>$this->items,
            'title'=>$this->title,
            'options'=>$this->options
        ]);
    }

}