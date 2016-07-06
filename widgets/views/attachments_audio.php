<?php

use yii\bootstrap\Html;

echo "<div class='row_audio'>";
foreach($items as $item){
    echo "<div class='row_audio_item attachment' data-attachment='".$item['type'].$item['audio']['owner_id'].'_'.$item['audio']['id']."'>";
    echo "<audio src='".$item['audio']['url']."' controls></audio>";
    echo "</div>";
}
echo "</div>";