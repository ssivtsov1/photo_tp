<?php
use yii\helpers\Html;

?>

<div class="view-photo">
    <? echo "<a href='site/detail_photo?id=$model->id&file_path=$model->file_path'".'>'."<img src=store/".$model->file_path.' class="img-rounded" ></a>'; ?>
    <div class="view-photo-data">
        <?=changeDateFormat($model->date, 'd.m.Y');?>

        <?php if(!empty($model->nazv_tp)): ?>
        <br>
        <?='Підстанція: '. $model->nazv_tp;?>
        <br>
        <?='Папка: '. $model->folder;?>
        <?php endif; ?>
        <?php if(!empty($model->description)): ?>
            <br>
            <?='Опис: '. $model->description;?>

        <?php endif; ?>
                
    </div>
</div>
