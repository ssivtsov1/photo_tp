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
        $(".field-view_photo_data-folder").hide();
        $("#view_photo_data-id_res").change();
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
            //initMap();
        }
    }

    function folder(d){
        if(d=='') {
            $("#view_photo_data-folder").val(d);

        }
        else {
            $("#view_photo_data-folder").val(d);

        }

    }
</script>

<div class="site-login">
    <h3><?= Html::encode($this->title) ?></h3>

    <p>Введіть параметри для пошуку фото:</p>

    <div class="row">
        <div class="col-xs-3">
            <?php $form = ActiveForm::begin(['id' => 'view_photo',]); ?>
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
            <?php if(Yii::$app->user->identity->id_res<>5000): ?>

            <?=$form->field($model, 'id_res')->dropDownList(ArrayHelper::map(app\models\spr_res::find()->all(), 'id', 'nazv'),
            [
            'prompt' => 'Виберіть РЕМ',
            'disabled' => 'disabled',
            'onchange' => '$.get("' . Url::to('/photo_tp/web/site/gettp_exists?id=') .
                    '"+$(this).val(),
                    function(data) {
                         //var tmp_work = $("#upload-id_tp").val();
                         $("#view_photo_data-id_tp").empty();
                         for(var ii = 0; ii<data.nazv.length; ii++) {
                         var q = data.nazv[ii].nazv;
                         //alert(q);
                         if(q==null) continue;
                         var q1 = q.substr(6);
                         var n = q.substr(0,6);
                         //var pr_rab = q.substr(4,1);
                         //if(geo_marker=="") 
                         $("#view_photo_data-id_tp").append("<option value="+n+">"+q1+"</option>");
                        } 
                  });',
                     ]
                    ) ?>
              <?php $model->id_res=Yii::$app->user->identity->id_res;  ?>
            <?php endif; ?>
            
            <?php if(Yii::$app->user->identity->id_res==5000): ?>
            <?=$form->field($model, 'id_res')->dropDownList(ArrayHelper::map(app\models\spr_res::find()->all(), 'id', 'nazv'),
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
            <?php endif; ?>

            <?=$form->field($model, 'id_tp')->
            dropDownList(ArrayHelper::map(
               app\models\spr_tp::findbysql('Select id,name_eqp from vw_sprav_tp')
                   ->all(), 'id', 'name_eqp'),
                    [
            'prompt' => 'Виберіть підстанцію',
                                 ]
                    ) ?>

            <?= $form->field($model, 'date')->
            widget(\yii\jui\DatePicker::classname(), [
                'language' => 'uk'
            ]) ?>
            <?= $form->field($model, 'date2')->
            widget(\yii\jui\DatePicker::classname(), [
                'language' => 'uk'
            ]) ?>
            <?= $form->field($model, 'description') ?>

            <?= $form->field($model, 'priz_description')->checkbox([
                'onchange' => 'showfields(this.checked);',
                'label' => 'Наявність опису',
                'labelOptions' => [
                    'style' => 'padding-left:20px;'
                ],
                'disabled' => false
            ]); ?>

            <?=$form->field($model, 'is_folder')->
            dropDownList(ArrayHelper::map(
                app\models\photo::findbysql('Select distinct min(id) as id,folder 
                from photo where id_res='.$model->id_res.' group by folder
                 union select -1 as id," " as folder order by folder' )
                    ->all(), 'id', 'folder'),
                ['onChange' => 'folder($(this).find(":selected").text())'
                ]
            )
            ?>
            <?= $form->field($model, 'folder') ?>

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

 <script type="text/javascript">

        // Определяем переменную map
        var map;
        var geo_marker = localStorage.getItem("geo_marker");
        if(geo_marker=='') {
            if (localStorage.getItem("geo_koord") == null) {
                localStorage.setItem("geo_lat", 48.446203);
                localStorage.setItem("geo_lng", 35.002512);
            }
        }
        // Функция initMap которая отрисует карту на странице
        function initMap() {
            var geo_marker = localStorage.getItem("geo_marker");
            if(geo_marker!='') {
            var lat1 = +localStorage.getItem("geo_lat");
            var lng1 = +localStorage.getItem("geo_lng");}
            else{
                var lat1 = +localStorage.getItem("geo_lat_res");
                var lng1 = +localStorage.getItem("geo_lng_res");
            }
            var idr = localStorage.getItem("id_res");


            if(lat1==48.446203)
            {    
            // В переменной map создаем объект карты GoogleMaps и вешаем эту переменную на <div id="map"></div>
            map = new google.maps.Map(document.getElementById('map_q'), {
                // При создании объекта карты необходимо указать его свойства
                // center - определяем точку на которой карта будет центрироваться
                center: {lat: 48.446203, lng: 35.002512},
               
                // zoom - определяет масштаб. 0 - видно всю планету. 18 - видно дома и улицы города.
                zoom: 17
                
            });
            }
            else
            {    
                map = new google.maps.Map(document.getElementById('map_q'), {
                // При создании объекта карты необходимо указать его свойства
                // center - определяем точку на которой карта будет центрироваться
                center: {lat: lat1, lng: lng1},
               
                // zoom - определяет масштаб. 0 - видно всю платнеу. 18 - видно дома и улицы города.
                zoom: 15
                
            });
            }
          
           
        var marker;
        $('.distance').val('');

 //           alert(localStorage.getItem("geo_marker"));
//            alert(localStorage.getItem("geo_k"));
            var geo_k = localStorage.getItem("geo_k");
            var geo_marker = localStorage.getItem("geo_marker");

        if(geo_marker!='') {
            //alert(geo_k);
//            var lat_save = localStorage.getItem("geo_lat_save");
//            var lon_save = localStorage.getItem("geo_lng_save");


            var myLatLng = {lat: lat1, lng: lng1};
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                //title: 'Hello World!'
            });


            lat1 = +localStorage.getItem("geo_lat_res");
            lng1 = +localStorage.getItem("geo_lng_res");

            //alert(localStorage.getItem("work"));
//            alert(idr);

            //            Уст. координат откуда будет ехать машина
            if(localStorage.getItem("work")=='sd'
                && ((idr==1) || (idr==2) || (idr==3) || (idr==7) || (idr==8) || (idr==11)))
            {
                //alert(1);
                var lat1 = +localStorage.getItem("geo_lat_sd");
                var lng1 = +localStorage.getItem("geo_lng_sd");
//                alert(lat1);
//                alert(lng1);
                var town = localStorage.getItem("town_sd");
                $('.primech').text('Увага! Машина їде з міста '+town+'.');
//                localStorage.setItem("work", '');
            }

            if(localStorage.getItem("work")=='sz'
                && ((idr==1) || (idr==2) || (idr==3) || (idr==6) || (idr==7) || (idr==11)))
            {
                //alert(2);
                var lat1 = +localStorage.getItem("geo_lat_sz");
                var lng1 = +localStorage.getItem("geo_lng_sz");

                var town = localStorage.getItem("town_sz");
                $('.primech').text('Увага! Машина їде з міста '+town+'.');
//                localStorage.setItem("work", '');
            }


            var url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins="+lat1+','+lng1+'&destinations=';
            url = url + geo_k;

           // alert(url);

            $.getJSON('/CalcWork/web/site/getdist?url='+url, function(data) {

                a=data.output.rows[0].elements[0].distance.value;
                a=Number(a)*2/1000;
                a=a.toFixed(2);
                $('.dst').val(a);
                adr = 'Адреса виконання робіт: '+data.output.destination_addresses;
                adr = adr.replace("Украина","Україна");
                $('.adr_potr').text(adr);
                $('#inputdataform-adr_potr').val(adr);
                $('#inputdataform-geo').val(k);
                //localStorage.setItem("geo_marker","1");

            });

        }

         google.maps.event.addListener(map, 'click', function(e) {
       
         var location = e.latLng;
         //localStorage.setItem("geo_marker","");
         $("#inputdataform-geo").val('1');
         $('.distance').val(location);
         
         if(marker != undefined) marker.setMap(null);
                         
         marker = new google.maps.Marker({
             position: location,
             map: map
           
         });
 
        var url,k;
         var geo_marker = localStorage.getItem("geo_marker");

         if(geo_marker=='') {
             var lat1 = localStorage.getItem("geo_lat");
             var lng1 = localStorage.getItem("geo_lng");
         }
             else {
             var lat1 = localStorage.getItem("geo_lat_res");
             var lng1 = localStorage.getItem("geo_lng_res");
         }

//             alert(lat1);
//             alert(lng1);

            $('.primech').text("");

//            Уст. координат откуда будет ехать машина
            if(localStorage.getItem("work")=='sd'
                && ((idr==1) || (idr==2) || (idr==3) || (idr==7) || (idr==8) || (idr==11)))
            {
                var lat1 = +localStorage.getItem("geo_lat_sd");
                var lng1 = +localStorage.getItem("geo_lng_sd");
                var town = localStorage.getItem("town_sd");
                $('.primech').text('Увага! Машина їде з міста '+town+'.');
            }

            if(localStorage.getItem("work")=='sz'
                && ((idr==1) || (idr==2) || (idr==3) || (idr==6) || (idr==7) || (idr==11)))
            {
                var lat1 = +localStorage.getItem("geo_lat_sz");
                var lng1 = +localStorage.getItem("geo_lng_sz");
                var town = localStorage.getItem("town_sz");
                $('.primech').text('Увага! Машина їде з міста '+town+'.');
            }

        if(lat1=="48.446203")
        url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=48.446203,35.002512&destinations=";
        else
        {
            url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins="+lat1+','+lng1+'&destinations=';
        }    
        k = location.toString();
           
        l = k.length;
        k = k.substring(1,l-1);
        url = url + k;

//         alert(url);

        $.getJSON('/CalcWork/web/site/getdist?url='+url, function(data) {
                                               
                a=data.output.rows[0].elements[0].distance.value;
                a=Number(a)*2/1000;
                a=a.toFixed(2);
                $('.dst').val(a);
                adr = 'Адреса виконання робіт: '+data.output.destination_addresses;
                adr = adr.replace("Украина","Україна");
                $('.adr_potr').text(adr);
                $('#inputdataform-adr_potr').val(adr);
                $('#inputdataform-geo').val(k);
               
         });
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
    




