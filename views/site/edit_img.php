<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
//use yii\helpers\Html;

$this->title = 'Редагування опису фото';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-spr">
    <div class="site-edit-photo">
    <div class="edit-photo-data1">
        <?=changeDateFormat($date, 'd.m.Y');?>
    </div>
    <?php if(!empty($nazv_tp)): ?>
        <div class="edit-photo-data2">
            <?='Підстанція: '. $nazv_tp;?>
        </div>
    <?php endif; ?>
        <br>
        <?= \yii\helpers\Html::img("@web/store/$img",['class'=>'img_edit']) ?>
    <?php $form = ActiveForm::begin(['id' => 'inputdata',
        'options' => [
            'class' => 'form-horizontal col-lg-5',

            'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']

            ]]]);
        $model->description=$descr;
        ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 3, 'cols' => 25]) ?>

        <div class="clearfix"> </div>
        <br>

        <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
    </div>
</div>



