<!--<script>-->
<!--    window.addEventListener('load', function() {-->
<!--        alert(111);-->
<!--        $("body").on("click", "a[href*='sprav/getEqp']", function (e) {-->
<!--            alert(111);-->
<!---->
<!--            $.ajax({-->
<!--                url: this,-->
<!--                dataType: "json",-->
<!--                success: function (data) {-->
<!--                    $(".modal-backdrop").html(data.name);-->
<!--                }-->
<!--            });-->
<!--        });-->
<!--    }-->
<!--</script>-->

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
    <?php $myPar = $model->id;  ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'emptyText' => 'Нічого не знайдено',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'header' => 'Обладнання',
                'value' => function($model) {
                    if(!is_null($model->eqp_cnt))
                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-cog"></span>',
                        ['sprav/equip?id='.$model->id],
                        ['title' => Yii::t('yii', 'Обладнання'), 'data-pjax' => '0']
                    );
                }
            ],
//            [
//                /**
//                 * Указываем класс колонки
//                 */
//                'class' => \yii\grid\ActionColumn::class,
//                 //$url => 'sprav/view&id='.$model->id,
//                 'buttons' => [
//                    'view' => function ($url, $model, $key) use($myPar){
//                        $options = [
//                            'title' => Yii::t('yii', 'Перегляд'),
//                            'aria-label' => Yii::t('yii', 'Перегляд'),
//                            'data-toggle' => Yii::t('yii', 'modal'),
//                            'data-target' => Yii::t('yii', '#w0'),
//                        ];
//                        if(!is_null($model->eqp_cnt))
//                            return Html::a('', ['getEqp','id' => $key], ['class' => 'glyphicon glyphicon-cog']);
//                       //   return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, $options);
//                    },
//                ],
//            'template' => '{view}',
//            ],

            'id',
            'name_eqp',
            'adr',
            'power',
            'name_v',
            'comp_cnt',
            'trans1',
            'trans2'
                        
        ],
    ]); ?>


    
</div>



