<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;

$this->title = 'Довідник підстанцій (всього '.$count.' підстанцій)';
$this->params['breadcrumbs'][] = $this->title;
if(Yii::$app->user->identity->role==3)
    $this->params['admin'][] = "Режим адміністратора";
?>
<?//= Html::a('Добавити', ['createtransp'], ['class' => 'btn btn-success']) ?>
<div class="site-spr">
    <h4><?= Html::encode($this->title) ?></h4>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'emptyText' => 'Нічого не знайдено',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name_eqp',
            'adr',
            'power'
                        
        ],
    ]); ?>


    
</div>



