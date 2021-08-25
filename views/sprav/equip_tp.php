<?php
// Вывод результата наличия оборудования

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use app\models\forExcel;
use app\models\sprav_tp;

use yii\bootstrap\Modal;

?>
<?php
Modal::begin([
//'header' => '<h3>Обладнання:</h3>',
//'toggleButton' => [
//'label' => 'Обладнання',
//'tag' => 'button',
//'class' => 'btn btn-success',
    'headerOptions' => ['id' => 'modalHeader','class'=>'text-center'],
    'header' => '<h2>Обладнання</h2>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE],
    'options'=>['style'=>'min-width:400px']
]);

$sql = "select u.name_eqp as equipment from  eqm_equipment_tbl a
                left join eqm_compens_station_inst_tbl b on a.id=b.code_eqp_inst
                left join (select * from  eqm_equipment_tbl) u on u.id=b.code_eqp
                where
                a.id=$id";
$model = sprav_tp::findBySql($sql)->asArray()->all();
echo $this->context->renderPartial('/sprav/eqp', [
    'model' => $model,
]);

?>

<?php
Modal::end();
?>

