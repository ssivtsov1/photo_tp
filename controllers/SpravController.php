<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\spr_res;
use app\models\spr_tp;
use app\models\sprav_tp;
use app\models\searchklient;
use app\models\requerstsearch;

class SpravController extends Controller
{
   public $spr='0';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    // Справочник РЭСов
    public function actionSprav_res()
    {
        $model = new spr_res();
        $model = $model::find()->all();
        $dataProvider = new ActiveDataProvider([
         'query' => spr_res::find(),
        ]); 
        $dataProvider->pagination->route = '/sprav/sprav_res';
        $dataProvider->sort->route = '/sprav/sprav_res';
        
            return $this->render('sprav_res', [
                'model' => $model,'dataProvider' => $dataProvider
            ]);
    }
    
    // Справочник подстанций (берется из таблиц на сервере PostGres)
    public function actionSprav_tp()
    {   
        $searchModel = new spr_tp();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $count = spr_tp::find()->count();

            return $this->render('sprav_tp', [
                'model' => $searchModel,'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,'count'=>$count
            ]);
    }

    // Оборудование (берется из таблиц на сервере PostGres)
    public function actionEquip($id)
    {
        $sql = "select u.name_eqp as equipment from  eqm_equipment_tbl a
                left join eqm_compens_station_inst_tbl b on a.id=b.code_eqp_inst
                left join (select * from  eqm_equipment_tbl) u on u.id=b.code_eqp
                where
                a.id=$id order by 1";
        $model = sprav_tp::findBySql($sql)->asArray()->all();

//        return $this->render('equip_tp', [
//            'model' => $model,'id' => $id
//        ]);
        return $this->render('eqp', [
            'model' => $model,'id' => $id
        ]);
    }

    // Подгрузка оборудования подстанций
    public function actionGetEqp($id)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $sql = "select u.name_eqp as equipment from  eqm_equipment_tbl a
                left join eqm_compens_station_inst_tbl b on a.id=b.code_eqp_inst
                left join (select * from  eqm_equipment_tbl) u on u.id=b.code_eqp
                where
                a.id=$id";
            $model = sprav_tp::findBySql($sql)->all();
            return ['success' => true, 'data' => $model];

        }
    }

//    Удаление записей из справочника
    public function actionDelete($id,$mod)
    {   // $id  id записи
        // $mod - название модели
        if($mod=='spr_res')
        $model = spr_res::findOne($id);
        $model->delete();
        
        if($mod=='spr_res')
        return $this->redirect(['sprav/sprav_res']);
    }

//    Обновление записей из справочника
    public function actionUpdate($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        if($mod=='spr_res')
        $model = spr_res::findOne($id);

        if ($model->load(Yii::$app->request->post()))
        {  
            
            if(!$model->save(false))
            {  var_dump($model);return;}

            if($mod=='spr_res')
                return $this->redirect(['sprav/sprav_res']);

        } else {
            if($mod=='spr_res')
            return $this->render('update_res', [
                'model' => $model,

            ]);
        }
    }
//    Срабатывает при нажатии кнопки добавления РЭСа
     public function actionCreateres()
    {
        $model = new spr_res();
       
        if ($model->load(Yii::$app->request->post()))
        {  
                       
            if($model->save(false)) //var_dump($model->getErrors());
               return $this->redirect(['sprav/sprav_res']);
           
        } else {
           
            return $this->render('update_res', [
                'model' => $model]);
        }
    }

}
