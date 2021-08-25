<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
?>
<div class="site-login">
    <h4><?= Html::encode("Обладнання:") ?></h4>
<?php
echo "</br>";
$cnt = count($model);
for($i=0;$i<$cnt;$i++){
    $j=($i+1).'.';
    echo "<span>$j &nbsp;&nbsp;</span>";
foreach($model[$i] as $k => $v) {
    echo $v;
}
    echo "</br>";
    echo "</br>";
}
?>


</div>