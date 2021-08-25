<?php
use yii\helpers\Html;

?>

<div class="view-photo">
    <? echo "<a href='site/detail_photo?id=$model->id&file_path=$model->file_path'".'>'."<img src=store/".$model->file_path.' class="img-rounded" ></a>'; ?>
    <div class="view-photo-data">
        <?=changeDateFormat($model->date, 'd.m.Y');?>
        <br>
        <?='Папка: '. $model->folder;?>
        <?php if(!empty($model->description)): ?>
            <br>
            <?='Опис: '. $model->description;?>

        <?php endif; ?>
                
    </div>
</div>
