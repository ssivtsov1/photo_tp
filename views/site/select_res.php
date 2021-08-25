<?php
/**
 * Created by PhpStorm.
 * User: ssivtsov
 * Date: 21.06.2017
 * Time: 9:43
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = 'Перегляд фото';
if(Yii::$app->user->identity->id_res<>5000)
$model->id_res = Yii::$app->user->identity->id_res;
?>

<script>
    // Показывает или прячет блок время работы (в зависимости от выбранной машины)
    // работает только при выборе транспортных услуг
    // если первый символ текста выбр. машины '-', тогда работа прячется, т.к. сумма работы в табл. Transport
    // для этого автомобиля не проставлена.


    window.onload=function(){
        //$("#inputdataform-potrebitel").hide();
        $("#select_res-id_res").change();

        }
    }
</script>

<div class="site-login">
<!--    <h3>--><?//= Html::encode($this->title) ?><!--</h3>-->

    <p>Введіть РЕМ:</p>

    <div class="row">
        <div class="col-xs-3">
            <?php $form = ActiveForm::begin(['id' => 'view_photo',]); ?>
            

            <?=$form->field($model, 'id_res')->dropDownList(
                    ArrayHelper::map(app\models\spr_res::findbysql("SELECT a.*,
                    concat(a.nazv,' (',case when b.kol is null then '0' else b.kol end,')') as nazva
                     FROM `spr_res` a left join 
                     (select id_res,count(*) as kol from vw_photo group by id_res) b
                      on a.id=b.id_res order by a.id ")->all(), 'id', 'nazva'),
            [
            'prompt' => 'Виберіть РЕМ',
            'onchange' => '$.get("' . Url::to('/photo_tp/web/site/gettp?id=') .
                    '"+$(this).val(),
                    function(data) {
                         //var tmp_work = $("#upload-id_tp").val();
                         $("#view_photo_data-id_tp").empty();
                         for(var ii = 0; ii<data.nazv.length; ii++) {
                         var q = data.nazv[ii].nazv;
                         //alert(q);
                         if(q==null) continue;
                         var q1 = q.substr(3);
                         var n = q.substr(0,3);
                         //var pr_rab = q.substr(4,1);
                         //if(geo_marker=="") 
                         $("#view_photo_data-id_tp").append("<option value="+n+">"+q1+"</option>");
                        } 
                  });',
                     ]
                    ) ?>
              <?php Yii::$app->user->identity->id_res = $model->id_res;  ?>

            <div class="form-group">
                <br>
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
            </div>
            <?php

            ActiveForm::end(); ?>
        </div>
    </div>
</div>





