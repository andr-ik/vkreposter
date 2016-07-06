<?php

namespace app\models;

use Yii;
use yii\base\Model;

class NewsfeedSearchForm extends Model
{
    public $q;
    public $sq;
    public $count = 10;

    private $stopWords = [];
    private $whiteWords = [];

    private function explodeQuery(){
        if (!empty($this->whiteWords)) return true;

        $this->q  && $this->whiteWords = array_map('trim', explode(' ', trim($this->q)));
        $this->sq && $this->stopWords  = array_map('trim', explode(' ', trim($this->sq)));

        return true;
    }

    public function rules()
    {
        return [
            [['q'], 'required'],
            [['sq'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'q'  => 'Искать',
            'sq' => 'Исключить слова'
        ];
    }

    public function getWhiteWords(){
        $this->explodeQuery();
        return $this->whiteWords;
    }

    public function getStopWords(){
        $this->explodeQuery();
        return $this->stopWords;
    }

    public function getQuery(){
        return implode(' ', $this->getWhiteWords());
    }
}