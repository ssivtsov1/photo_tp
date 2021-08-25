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

$this->title = 'Перегляд фото ';
$this->params['breadcrumbs'][] = $this->title;

$data = $dataProvider->getModels();


//$photo = $data->getImage();


?>

<div class="site-spr">
    <?php if($id_tp>0): ?>
        <h4><?= Html::encode($this->title).'(підстанція '.$data[0]->nazv_tp.')' ?></h4>
    <?php endif; ?>
    <?php if($id_tp<0): ?>
        <h4><?= Html::encode($this->title) ?></h4>
    <?php endif; ?>


<?php
if($id_tp<0) {

    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_item', ['model' => $model]);
        },
        'itemView' => '_item',
        'layout' => "{pager}\n{summary}\n{items}",

//        'pager' => [
//            'firstPageLabel' => 'Первая',
//            'lastPageLabel' => 'Последняя',
//            'nextPageLabel' => 'Следующая',
//            'prevPageLabel' => 'Предыдущая',
//            'maxButtonCount' => 3,
//        ], 'pager' => [
//            'firstPageLabel' => 'Первая',
//            'lastPageLabel' => 'Последняя',
//            'nextPageLabel' => 'Следующая',
//            'prevPageLabel' => 'Предыдущая',
//            'maxButtonCount' => 3,
//        ],
//        'pager' => [
//            'pagination' => $dataProvider->setPagination(['pageSize' => 4])
//        ],


    ]);
}
else
{
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_item', ['model' => $model]);
        },
        'itemView' => '_itemtp',
        'layout' => "{pager}\n{summary}\n{items}",


    ]);
}
?>
</div>



