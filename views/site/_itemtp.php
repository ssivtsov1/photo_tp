<?php
use yii\helpers\Html;

?>

<div class="view-photo">
    <? echo "<a href='site/detail_photo?&id=$model->id'".'>'."<img src=store/".$model->file_path.' class="img-rounded" ></a>'; ?>
    <div class="view-photo-data">
        <?=changeDateFormat($model->date, 'd.m.Y');?>
        <?php if(!empty($model->description)): ?>
            <br>
            <?='Опис: '. $model->description;?>
        <?php endif; ?>
                
    </div>
</div>
