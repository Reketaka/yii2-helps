<?php

namespace reketaka\helps\common\models;

use Yii;

class DebugModule extends \yii\debug\Module
{
    private $_basePath;

    public $usernamesAllows = ['superadmin'];

    protected function checkAccess()
    {
        try {
            $user = Yii::$app->getUser();

            if (($userIdentity = $user->identity) && (in_array($userIdentity->username, $this->usernamesAllows))) {
                return true;
            }
        }catch (\Exception $exception){
            Yii::error($exception->getMessage(), __METHOD__);
        }

        return parent::checkAccess();
    }

    /**
     * Returns the root directory of the module.
     * It defaults to the directory containing the module class file.
     * @return string the root directory of the module.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass(new yii\debug\Module('debug'));
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }
}