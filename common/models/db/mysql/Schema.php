<?php

namespace reketaka\helps\common\models\db\mysql;


class Schema extends \yii\db\mysql\Schema{

    public function createColumnSchemaBuilder($type, $length = null)
    {
        return new ColumnSchemaBuilder($type, $length, $this->db);
    }

}