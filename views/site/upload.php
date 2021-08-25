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
use yii\jui\AutoComplete;

$this->title = 'Завантаження фото';
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
        $("#upload-id_res").change();

        $('#upload-image').click(function(){
            $('.label-success').removeClass('label-success');
            $('.label-success').text('');
            $('.label-danger').removeClass('label-danger');
            $('.label-danger').text('');
        });
        localStorage.setItem("data_tp", '');
        localStorage.setItem("geo_res", '');
        localStorage.setItem("geo_lat", '');
        localStorage.setItem("geo_lng", '');
        localStorage.setItem("geo_lat_sd", '');
        localStorage.setItem("geo_lng_sd", '');
        localStorage.setItem("geo_lat_sz", '');
        localStorage.setItem("geo_lng_sz", '');
        localStorage.setItem("id_res", '');
        localStorage.setItem("usluga", '');
        //localStorage.setItem("work", '');
        localStorage.setItem("town_sz", '');
        localStorage.setItem("town_sd", '');
        var geo,y1,p1,lat,lon;
        geo = $("#inputdataform-geo").val();
        localStorage.setItem("geo_marker", '');
        localStorage.setItem("geo_k", '');
        //alert(geo);
        if(geo!='') {
            y1 = geo.length;
            p1 = geo.indexOf(',') - 1
            lat = geo.substring(0, p1);
            lon = geo.substring(p1 + 2);
            localStorage.setItem("geo_lat", lat);
            localStorage.setItem("geo_lng", lon);
            localStorage.setItem("geo_lat_save", lat);
            localStorage.setItem("geo_lng_save", lon);
            localStorage.setItem("geo_marker", '('+geo+')');
            localStorage.setItem("geo_k", geo);
//            alert(localStorage.getItem("geo_marker"));
//            alert(localStorage.getItem("geo_k"));
            $("#inputdataform-res").change();
            setTimeout(function () {
                 initMap();      
                    }, 600); // время в мс
        }
    }

    function folder(d){
        if(d=='') {
            $("#upload-folder").val(d);
            $(".field-upload-folder").show();

        }
        else {
            $("#upload-folder").val(d);
            $(".field-upload-folder").hide();

        }

    }
</script>

<div class="site-login">
    <h3><?= Html::encode($this->title) ?></h3>

<!--    <p>Введіть параметри для розрахунку:</p>-->
    <div class="row">
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin(['id' => 'upload',
                'options' => [
                    'class' => 'form-horizontal col-lg-25',
                    'enctype' => 'multipart/form-data'
                    
                ]]); ?>

                
            <?php
            if(Yii::$app->user->identity->role == 3)
            {
                $session = Yii::$app->session;
                $session->open();
                if($session->has('id_res'))
                {
                    Yii::$app->user->identity->id_res = $session->get('id_res');
                    $model->id_res=Yii::$app->user->identity->id_res;
                }
            }
            ?>
            <?php   if(Yii::$app->user->identity->id_res<>5000): ?>
            <?=$form->field($model, 'id_res')->dropDownList(ArrayHelper::map(app\models\spr_res::find()->all(), 'id', 'nazv'),
            [
            'prompt' => 'Виберіть РЕМ',
            'disabled' => 'disabled',
             ]) ?>
                <?php $model->id_res=Yii::$app->user->identity->id_res;  ?>
            <?php endif; ?>
            
            <?php if(Yii::$app->user->identity->id_res==5000): ?>
            <?=$form->field($model, 'id_res')->dropDownList(ArrayHelper::map(app\models\spr_res::find()->all(), 'id', 'nazv'),
            [
            'prompt' => 'Виберіть РЕМ',
            ]); ?>
            <?php endif; ?>

            <?=$form->field($model, 'search_tp')->textInput(
                ['maxlength' => true,'onkeyup' => '$.get("' . Url::to('/photo_tp/web/site/get_search_tp?name=') .
                    '"+$(this).val(),
                   function(data) {
                         $("#upload-id_tp").empty();
                        
                         for(var ii = 0; ii<data.tp.length; ii++) {
                         
                         var q1 = data.tp[ii].name_eqp;
                         var n = data.tp[ii].id;
//                         alert(q1);
//                         alert(n);
                         if(q1==null) continue;
//                         var q1 = q.substr(6);
//                         var n = q.substr(0,6);
//                         alert("<option value="+n+">"+q1+"</option>");
                         $("#upload-id_tp").append("<option value="+n+">"+q1+"</option>");
                        } 
                  });'
                ]) ?>



            <?=$form->field($model, 'id_tp')->
            dropDownList(ArrayHelper::map(
                app\models\spr_tp::findbysql('Select id,name_eqp from vw_sprav_tp')
                    ->all(), 'id', 'name_eqp'),
                [   'onChange' => 'view_on_Map($(this).val())',
                    'prompt' => 'Виберіть підстанцію',
                ]
            ) ?>




<!--            --><?//
            //фомируем список
//            $listdata=app\models\spr_tp::findbysql('Select id,name_eqp from vw_sprav_tp')
//                ->asArray()->all();
//            ?>
<!--            --><?////Передаем список виджету AutoComplete?>
<!--            --><?//= $form->field($model, 'id_tp')->widget(
//                AutoComplete::className(), [
//                'clientOptions' => [
//                    'source' => $listdata,
//                ],
//                'options'=>[
//                    'class'=>'form-control'
//                ]
//            ]);
//            ?>




            <?= $form->field($model, 'description') ?>

            <?=$form->field($model, 'is_folder')->
            dropDownList(ArrayHelper::map(
                app\models\photo::findbysql('Select distinct min(id) as id,folder 
                from photo where id_res='.$model->id_res.' group by folder
                 union select -1 as id," " as folder order by folder')
                    ->all(), 'id', 'folder'),
                [ 'onChange' => 'folder($(this).find(":selected").text())'
                ]
            )
            ?>
            <?= $form->field($model, 'folder') ?>
<!--            --><?//= $form->field($model, 'image')->fileInput(); ?>
            <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true,'accept' => 'image/*']) ?>

            <?php if(Yii::$app->session->hasFlash('success')):?>
                <span class="label label-success" ><?php echo Yii::$app->session->getFlash('success'); ?></span>
            <?php endif; ?>

            <?php if(Yii::$app->session->hasFlash('Error')):?>
                <span class="label label-danger" ><?php echo Yii::$app->session->getFlash('Error'); ?></span>

            <?php endif; ?>
            <?php if(!Yii::$app->session->hasFlash('Error')):?>
                <?php echo ' '; ?>

            <?php endif; ?>


<!--            <p>Виберіть на карті місце виконання робіт (для обліку транспортних витрат):</p>-->
<!--            <div id="map_q"></div>-->

            <div class="form-group">
                <br>
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
            </div>
            <?php

            ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div id="map_q"></div>

 <script type="text/javascript">
        function view_on_Map(tp) {
            //alert(tp);
            localStorage.setItem("data_tp", tp);
            setTimeout(function () {
                initMap();
            }, 200); // время в мс
            setTimeout(function () {
                initMap();
            }, 800); // время в мс


        }
        // Определяем переменную map
        var map;
                          
        // Функция initMap которая отрисует карту на странице
        function initMap() {
                
            //var tp = "<?php echo $model->id_tp; ?>";
            var tp = localStorage.getItem("data_tp");
            //alert(tp);
                
            $.getJSON('/photo_tp/web/site/get_tp?name='+tp, function(data) {
                // alert(data.lat);
                 localStorage.setItem("lat1", data.lat);
                 localStorage.setItem("lon1", data.lon);
                
            });
            
            var lat1 = +localStorage.getItem("lat1");
            var lon1 = +localStorage.getItem("lon1");
            
            //alert(localStorage.getItem("lat1"));     
                       
            // В переменной map создаем объект карты GoogleMaps и вешаем эту переменную на <div id="map"></div>
            map = new google.maps.Map(document.getElementById('map_q'), {
                // При создании объекта карты необходимо указать его свойства
                // center - определяем точку на которой карта будет центрироваться
                center: {lat: lat1, lng: lon1},
               
                // zoom - определяет масштаб. 0 - видно всю планету. 18 - видно дома и улицы города.
                zoom: 17
            });
           
        var marker;

            var myLatLng = {lat: lat1, lng: lon1};
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');

      }

   map.setMapDisplayLanguage(new Locale("ru"));
   map.setMapSecondaryDisplayLanguage(new Locale("ru"));


    </script>

    
    <script
        window.onload=function(){
        localStorage.setItem("geo_res", '');
        localStorage.setItem("geo_lat", '');
        localStorage.setItem("geo_lng", '');
        localStorage.setItem("geo_lat_sd", '');
        localStorage.setItem("geo_lng_sd", '');
        localStorage.setItem("geo_lat_sz", '');
        localStorage.setItem("geo_lng_sz", '');
        localStorage.setItem("id_res", '');
        localStorage.setItem("usluga", ' ');
        }

src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSyQ_ATqeReytiFrTiqQAS9FyIIwuHQS4&callback=initMap&language=ru&region=UA"
        async defer>
            
    </script>
    




