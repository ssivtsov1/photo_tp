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
//$img = $_GET['file_path'];

$img = $file_path;

?>
<script>
 window.onload=function(){
     localStorage.setItem("angle", 0);
     localStorage.setItem("q", 0);
     $('#rotate').bind('click', function(){
              var angle = Number(localStorage.getItem("angle"))+90;
              var q = Number(localStorage.getItem("q"))+1;
              localStorage.setItem("q", q);
              localStorage.setItem("angle", angle);
              //$(".img_detail").rotate(angel);

              $(".img_detail").rotate({animateTo:angle});

              if(q%2==1) {
                  $(".img_detail").css('margin-top', '100px');
                  $("#map_q").css('margin-top', '160px');
                  $(".span_single").css('display', 'none');
              }
              else {
                  $(".img_detail").css('margin-top', '12px');
                  $("#map_q").css('margin-top', '15px');
                  $(".span_single").css('display', 'inline');
              }
     });

         var tp = "<?php echo $id_tp; ?>";
         setTimeout(function () {
                 initMap();      
                    }, 600); // время в мс

     $('.img_detail').lightzoom({zoomPower   : 3.5});

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
        <button class='btn btn-primary' id="rotate" > Поворот фото </button>
        <br>
        <br>
        <?php if(!empty($descr)): ?>
         <?= Html::a('| Змінити опис |',["edit_img?&id=$id&img=$img"], ['class' => 'btn btn-primary']); ?>
        <?php endif; ?>
        <?php if(empty($descr)): ?>
            <?= Html::a('| Зробити опис |',["edit_img?&id=$id&img=$img"], ['class' => 'btn btn-primary']); ?>
        <?php endif; ?>

    </div>
    <?= Html::a('Видалити фото', ['del_img', 'id' => $id,'file_path' => $img], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Ви впевнені, що хочете видалити це фото?',
            'method' => 'post',
        ]]);  ?>
</div>


<div class="clearfix"></div>
<br>
