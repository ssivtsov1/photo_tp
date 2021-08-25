<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\Request;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php


        $flag = 1;
        $main = 0;

         if(!isset(Yii::$app->user->identity->id_res))
                $flag=0;
         else
             $flag = Yii::$app->user->identity->id_res;

                 

       // die;
        if(isset(Yii::$app->user->identity->role)) {
                $adm = Yii::$app->user->identity->role;
                if ($adm==3)
                {
                    $main=1;
                    $this->params['admin'][] = "Режим адміністратора: ";
                }
                else
                    $this->params['admin'][] = "Режим користувача: ";
         }

        $session = Yii::$app->session;
        $session->open();
        if($session->has('id_res'))
            $this->params['res'][] = $session->get('nazv_res');

        if(!isset(Yii::$app->user->identity->role))
            $main=2;
        if(!isset(Yii::$app->user->identity->id_res))
            $main=2;

            NavBar::begin([
                'brandLabel' => 'Фотографії підстанцій',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);


        switch ($main) {
            case 2:
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => [

                       // ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                    ],
                ]);
                break;

            case 1:
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => [
                        ['label' => 'Головна', 'url' => ['/site/view_photo']],
                        ['label' => 'Вибрати РЕМ', 'url' => ['/site/select_res']],

                        ['label' => 'Довідники', 'url' => ['/site/index'],
                            'options' => ['id' => 'down_menu'],
                            'items' => [
                                ['label' => 'Довідник РЕМів', 'url' => ['/sprav/sprav_res']],
                                ['label' => 'Довідник підстанцій', 'url' => ['/sprav/sprav_tp']],

                            ]],

                        ['label' => 'Перегляд фото', 'url' => ['/site/view_photo']],
                        ['label' => 'Завантаження фото', 'url' => ['/site/upload']],
                        ['label' => 'Про программу', 'url' => ['/site/about']],
                        ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                        /*
                        Yii::$app->user->isGuest ?
                            ['label' => 'Login', 'url' => ['/site/login']] :
                            ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                                'url' => ['/site/logout'],
                                'linkOptions' => ['data-method' => 'post']],
                         *
                         */
                    ],
                ]);
                break;
            case 0:
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => [
                        ['label' => 'Головна', 'url' => ['/site/view_photo']],

                        ['label' => 'Довідники', 'url' => ['/site/index'],
                            'options' => ['id' => 'down_menu'],
                            'items' => [
                                ['label' => 'Довідник РЕМів', 'url' => ['/sprav/sprav_res']],
                                ['label' => 'Довідник підстанцій', 'url' => ['/sprav/sprav_tp']],

                            ]],

                        ['label' => 'Перегляд фото', 'url' => ['/site/view_photo']],
                        ['label' => 'Завантаження фото', 'url' => ['/site/upload']],
                        ['label' => 'Про программу', 'url' => ['/site/about']],
                        ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                        /*
                        Yii::$app->user->isGuest ?
                            ['label' => 'Login', 'url' => ['/site/login']] :
                            ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                                'url' => ['/site/logout'],
                                'linkOptions' => ['data-method' => 'post']],
                         *
                         */
                    ],
                ]);
                break;
        }
            NavBar::end();
        ?>


        <!--Вывод логотипа-->
        <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
        <? if(strlen(Yii::$app->request->url)==10): ?>
        <img class="logo_site" src="web/Logo.png" alt="ЦЕК" />
        <? endif; ?>

        <? if(strlen(Yii::$app->request->url)<>10): ?>
            <img class="logo_site" src="../Logo.png" alt="ЦЕК" />
        <? endif; ?>
        <? endif; ?>

        <? if(strpos(Yii::$app->request->url,'/cek')): ?>
            <? if(strlen(Yii::$app->request->url)==13): ?>
                <img class="logo_site" src="web/Logo.png" alt="ЦЕК" />
            <? endif; ?>

            <? if(strlen(Yii::$app->request->url)<>13): ?>
                <img class="logo_site" src="../Logo.png" alt="ЦЕК" />
            <? endif; ?>
        <? endif; ?>

        <? if(!strpos(Yii::$app->request->url,'/web')): ?>
        <div class="r_sidebar">
            <img class="face_pict" src="./plant-growing-in-a-bulb_1232-194.jpg" alt="ЦЕК" />
        </div>  
        <? endif; ?>
        
        <div class="container">

            <div class="page-header">
                <small class="text-info">
                    <?php
                    if(isset($this->params['admin'] ))
                        if(isset($this->params['res'] ))
                        echo $this->params['admin'][0] . ' '. $this->params['res'][0];
                    ?>
                    </small>

            </div>

            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Головна', 'url' => '/photo_tp'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
<!--            --><?//= phpinfo(); ?>
            <?= $content ?>
        </div>
        
        
        
    </div>
    
    

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; ЦЕК <?= date('Y') ?></p>
            <p class="pull-right"><?//= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
