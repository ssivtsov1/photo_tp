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

$this->title = 'Перегляд фото (детально)';
$this->params['breadcrumbs'][] = $this->title;

?>
<script>
 window.onload=function(){
         var tp = "<?php echo $nazv_tp; ?>";
         
         setTimeout(function () {
                 initMap();      
                    }, 600); // время в мс
        }
</script>

<div class="site-spr">
    <div class="site-detail">
    <h4><?= Html::encode($this->title) ?></h4>
    <!--<? echo "<img src=store/".$img.'>'; ?>-->
    <div class="detail-photo">
        <div class="detail-photo-data1">
            <?=changeDateFormat($date, 'd.m.Y');?> 
        </div>
        <?php if(!empty($nazv_tp)): ?>
            <div class="detail-photo-data2">
                <?='Підстанція: '. $nazv_tp;?>
            </div>
        <?php endif; ?>
        <?php if(!empty($descr)): ?>
             <div class="detail-photo-data2">
                <?='Опис: '. $descr;?>
            </div>
        <?php endif;?>
        <?= \yii\helpers\Html::img("@web/store/$img",['class'=>'img_detail']) ?>
    </div>
    
    <br>
    
    </div>
    <div class="form-group">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <?php if(!empty($descr)): ?>
         <?= Html::a('| Змінити опис |',["edit_img?&id=$id"], ['class' => 'btn btn-primary']); ?>
        <?php endif; ?>
        <?php if(empty($descr)): ?>
            <?= Html::a('| Зробити опис |',["edit_img?&id=$id"], ['class' => 'btn btn-primary']); ?>
        <?php endif; ?>

    </div>
<!--    --><?//= Html::a('Видалити фото',["del_img?&id=$id"], ['class' => 'btn btn-danger']); ?>
    <?= Html::a('Видалити фото', ['del_img', 'id' => $id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Ви впевнені, що хочете видалити це фото?',
            'method' => 'post',
        ]]);  ?>
</div>


<div class="clearfix"></div>
<br>
<br>
<span class="span_single">
    <?= Html::encode("Місцерозположення підстанції:") ?>
</span>    
<br>
<div id="map_q"></div>

<script type="text/javascript">

        // Определяем переменную map
        var map;
                          
        // Функция initMap которая отрисует карту на странице
        function initMap() {
                
                var tp = "<?php echo $nazv_tp; ?>";
                
            $.getJSON('/photo_tp/web/site/get_tp?name='+tp, function(data) {
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
        
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSyQ_ATqeReytiFrTiqQAS9FyIIwuHQS4&callback=initMap&language=ru&region=UA"
        async defer>
            localStorage.setItem("lat1", 0);
            localStorage.setItem("lon1", 0);
</script>

