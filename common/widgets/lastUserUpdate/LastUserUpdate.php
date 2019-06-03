<?php

namespace reketaka\helps\common\widgets\lastUserUpdate;


use common\helpers\BaseHelper;
use kartik\select2\Select2;
use yii\db\ActiveRecord;
use yii\grid\Column;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

class LastUserUpdate extends Column
{

    public $userAttribute = 'owner_id';

    public $updateAtAttribute = 'updated_at';

    public $userRelationName = 'owner';

    public $userTitle = 'username';

    public $headerOptions = ['style' => 'width:100px;'];

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        /**
         * @var $model ActiveRecord
         */

        if (!$model->hasAttribute($this->userAttribute)) {
            return null;
        }

        $content = [];
        $content[] = "<b>{$model->getAttributeLabel($this->updateAtAttribute)}</b> {$model->{$this->updateAtAttribute}}<BR>";

        if($model->getRelation($this->userRelationName) && ($user = $model->{$this->userRelationName})){
            $content[] = "<b>{$model->getAttributeLabel($this->userAttribute)}</b> {$user->{$this->userTitle}}<BR>";
        }


        $content = implode('', $content);
        return $this->grid->formatter->format($content, 'html');
    }


}