<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class AttachmentsPhotoWidget extends Widget
{
    private $type = 'photo';

    public $attachments = [];

    public function init(){
        $this->attachments = array_filter($this->attachments, function($item){
            return $item['type'] === $this->type;
        });

        parent::init();
    }

    public function run()
    {
        return $this->render('attachments_'.$this->type, [
            'items' => $this->attachments
        ]);
    }
}