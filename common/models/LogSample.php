<?php

namespace reketaka\helps\common\models;


use Carbon\Carbon;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\BaseObject;
use yii\helpers\FileHelper;
use function file_put_contents;
use function implode;
use function realpath;
use const FILE_APPEND;

class LogSample extends BaseObject{

    CONST DEFAULT_DIR = "@app/runtime/sample_logs";

    public $enable = true;

    private $name = null;

    private string|null $uniqId = null;

    /**
     * Путь до папки где хранится файл лога
     * @var null
     */
    private $logDirPath = null;

    /**
     * Полный путь до файла лога
     * @var string|null
     */
    private $logFullPath = null;

    /**
     * Пул сообщений модели
     * @var array
     */
    private $messages = [];

    /**
     * @param bool $v
     * @return $this
     */
    public function setEnable($v = true){
        $this->enable = $v;
        return $this;
    }

    /**
     * Устанавливает базовый полный путь до директории с логами
     */
    public function setDirPath($path){
        $this->logDirPath = Yii::getAlias($path);
        return $this;
    }

    public function setPath($path){
        $this->logFullPath = Yii::getAlias($this->logDirPath."/".$path);
        return $this;
    }

    /**
     * Устанавливает название лога
     * @param $var
     * @return object
     */
    public function setName($var):object{
        $this->name = $var;
        return $this;
    }

    /**
     * Генерирует уникальный id для сессия логов
     * @return $this
     * @throws \yii\base\Exception
     */
    public function generateUniqId(){
        $this->uniqId = Yii::$app->security->generateRandomString(8);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(){
        return $this->uniqId;
    }

    private function checkPermissions():void{
        if(!is_writeable($this->logDirPath)){
            Yii::error("Директория для записи логов не доступна для записи {$this->logDirPath}");
            $this->enable = false;
        }

    }

    public function init(){

        $this->setDirPath(self::DEFAULT_DIR);
        $this->setPath("sample_".$this->name.".log");

        $this->generateUniqId();

        FileHelper::createDirectory($this->logDirPath);

        $this->checkPermissions();

    }

    /**
     * Записывает сообщение в логи
     * @param $var
     */
    public function fill($var):void{
        if(!$this->enable){
            return;
        }

        $data = [
            $this->uniqId,
            Carbon::now()->toDateTimeString(),
            $var
        ];

        $logMessage = implode(" ", $data).PHP_EOL;

        $this->messages[] = $logMessage;

        file_put_contents($this->logFullPath, $logMessage, FILE_APPEND);
    }

    /**
     * Возвращает весь пул сообщений которые были записаны в файл в рамках жизни модели
     * @return array
     */
    public function getFills(){
        return $this->messages;
    }

    /**
     * Сбрасывает пул сообщений
     * @return $this
     */
    public function flushFills(){
        $this->messages = [];
        return $this;
    }


}