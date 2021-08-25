<?php

namespace app\controllers;

use app\models\Control_photo;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\ContactForm;
use app\models\spr_res;
use app\models\spr_tp;
use app\models\spr_tp_mysql;
use app\models\sprav_tp;
use app\models\klient;
use app\models\upload;
use app\models\select_res;
use app\models\view_photo;
use app\models\edit_photo;
use app\models\image;
use app\models\pg;
use app\models\photo;
use app\models\geo_tp;
use app\models\cphoto;
use app\models\view_photo_data;
use app\models\requestsearch;
use app\models\info;
use app\models\User;
use app\models\loginform;
use yii\web\UploadedFile;

class SiteController extends AppController  //Controller
{  /**
 * 
 * @return type
 *
 */
    //public $defaultAction = 'index';
    public $layout = 'main';

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
            'image' => [
                'class' => 'circulon\images\actions\ImageAction',

                // all the model classes to be searched by this action.
                // Can be fully qualified namespace or alias
                'models' => ['upload,view_photo']
	        ]
        ];
    }


    //  Происходит при запуске сайта
    public function actionIndex()
    {

        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['site/more']);
        }
//
//        if(strpos(Yii::$app->request->url,'/cek')==0)
//            return $this->redirect(['site/more']);
//
//        $model = new loginform();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->redirect(['site/more']);
//        } else {
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
        
        $model = new loginform();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/more']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }

    }

//  Происходит при входе в пункт меню Вибрати РЕМ (срабатывает только под админом)
    public function actionSelect_res()
    {
        $model = new Select_res();
        if ($model->load(Yii::$app->request->post()))
        {

            Yii::$app->user->identity->id_res = $model->id_res;
//            debug(Yii::$app->user->identity->id_res);
//            die;

            $res=spr_res::find()->select('nazv')->where(['id' => Yii::$app->user->identity->id_res])->all();
            $nazv_res =  $res[0]->nazv;
//            debug(Yii::$app->controller->message);
//            die;

            $data = new sprav_tp();
//        Создаем вид на сервере PostGres, откуда берем список подстанций
            $sql = "CREATE OR REPLACE VIEW vw_sprav_tp as SELECT eq.id,
        eq.name_eqp,
        eq.num_eqp,
        v.voltage_min as name_v,
        eq.dt_install,
        eq.id_addres,
        eqa.id_client,
        a.adr::character varying AS adr,
        u.eqp_cnt,
        s.power,
        s.comp_cnt,
        s.p_regday,
        s.date_regday,
        s.id_type1,
        s.id_type2,
        tr1.type as trans1,
        tr2.type as trans2
       FROM eqm_equipment_tbl eq
         JOIN eqm_area_tbl eqa ON eqa.code_eqp = eq.id
         LEFT JOIN adv_address_tbl a ON eq.id_addres = a.id
         JOIN eqm_compens_station_tbl s ON eq.id = s.code_eqp
         LEFT JOIN eqk_voltage_tbl v ON s.id_voltage = v.id
         LEFT JOIN eqi_compensator_tbl tr1 ON s.id_type1 = tr1.id
         LEFT JOIN eqi_compensator_tbl tr2 ON s.id_type2 = tr2.id
         LEFT JOIN ( SELECT eqm_compens_station_inst_tbl.code_eqp_inst,
                count(*)::integer AS eqp_cnt
               FROM eqm_compens_station_inst_tbl
              GROUP BY eqm_compens_station_inst_tbl.code_eqp_inst
              ORDER BY eqm_compens_station_inst_tbl.code_eqp_inst) u ON eq.id = u.code_eqp_inst
      WHERE eq.type_eqp = 8 and eqa.id_client=2062
      ORDER BY eq.name_eqp";
            if(Yii::$app->user->identity->id_res==1)
                Yii::$app->db_1->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==2)
                Yii::$app->db_2->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==3)
                Yii::$app->db_3->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==4)
                Yii::$app->db_4->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==5)
                Yii::$app->db_5->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==6)
                Yii::$app->db_6->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==7)
                Yii::$app->db_7->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==8)
                Yii::$app->db_8->createCommand($sql)->execute();

            $session = Yii::$app->session;
            $session->open();
            $session->set('id_res', Yii::$app->user->identity->id_res);
            $session->set('nazv_res', $nazv_res);
            
            return $this->redirect(['viewphoto_main','id_res' => Yii::$app->user->identity->id_res]);

        }

        else {
            return $this->render('select_res', [
                'model' => $model,
            ]);
        }
    
    }
    //  Происходит после ввода пароля
    public function actionMore()
    {
    if(Yii::$app->user->identity->role==3)
//        Если вход под админом
    {
        $model = new Select_res();
        if ($model->load(Yii::$app->request->post()))
        {

            Yii::$app->user->identity->id_res = $model->id_res;
//            debug(Yii::$app->user->identity->id_res);
//            die;
            $res=spr_res::find()->select('nazv')->where(['id' => Yii::$app->user->identity->id_res])->all();
            $nazv_res =  $res[0]->nazv;

            $data = new sprav_tp();

//        Создаем вид на сервере PostGres, откуда берем список подстанций
            $sql = "CREATE OR REPLACE VIEW vw_sprav_tp as SELECT eq.id,
        eq.name_eqp,
        eq.num_eqp,
        v.voltage_min as name_v,
        eq.dt_install,
        eq.id_addres,
        eqa.id_client,
        a.adr::character varying AS adr,
        u.eqp_cnt,
        s.power,
        s.comp_cnt,
        s.p_regday,
        s.date_regday,
        s.id_type1,
        s.id_type2,
        tr1.type as trans1,
        tr2.type as trans2
       FROM eqm_equipment_tbl eq
         JOIN eqm_area_tbl eqa ON eqa.code_eqp = eq.id
         LEFT JOIN adv_address_tbl a ON eq.id_addres = a.id
         JOIN eqm_compens_station_tbl s ON eq.id = s.code_eqp
         LEFT JOIN eqk_voltage_tbl v ON s.id_voltage = v.id
         LEFT JOIN eqi_compensator_tbl tr1 ON s.id_type1 = tr1.id
         LEFT JOIN eqi_compensator_tbl tr2 ON s.id_type2 = tr2.id
         LEFT JOIN ( SELECT eqm_compens_station_inst_tbl.code_eqp_inst,
                count(*)::integer AS eqp_cnt
               FROM eqm_compens_station_inst_tbl
              GROUP BY eqm_compens_station_inst_tbl.code_eqp_inst
              ORDER BY eqm_compens_station_inst_tbl.code_eqp_inst) u ON eq.id = u.code_eqp_inst
      WHERE eq.type_eqp = 8 and eqa.id_client=2062
      ORDER BY eq.name_eqp";
            if(Yii::$app->user->identity->id_res==1)
                Yii::$app->db_1->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==2)
                Yii::$app->db_2->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==3)
                Yii::$app->db_3->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==4)
                Yii::$app->db_4->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==5)
                Yii::$app->db_5->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==6)
                Yii::$app->db_6->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==7)
                Yii::$app->db_7->createCommand($sql)->execute();
            if(Yii::$app->user->identity->id_res==8)
                Yii::$app->db_8->createCommand($sql)->execute();

            $session = Yii::$app->session;
            $session->open();
            $session->set('id_res', Yii::$app->user->identity->id_res);
            $session->set('nazv_res', $nazv_res);

            return $this->redirect(['viewphoto_main','id_res' => Yii::$app->user->identity->id_res]);


        }

        else {
            return $this->render('select_res', [
                'model' => $model,
            ]);
        }
    }


        $res=spr_res::find()->select('nazv')->where(['id' => Yii::$app->user->identity->id_res])->all();
        $nazv_res =  $res[0]->nazv;
        $session = Yii::$app->session;
        $session->open();
        $session->set('id_res', Yii::$app->user->identity->id_res);
        $session->set('nazv_res', $nazv_res);

    $data = new sprav_tp();
//        Создаем вид на сервере PostGres, откуда берем список подстанций
        $sql = "CREATE OR REPLACE VIEW vw_sprav_tp as SELECT eq.id,
        eq.name_eqp,
        eq.num_eqp,
        v.voltage_min as name_v,
        eq.dt_install,
        eq.id_addres,
        eqa.id_client,
        a.adr::character varying AS adr,
        u.eqp_cnt,
        s.power,
        s.comp_cnt,
        s.p_regday,
        s.date_regday,
        s.id_type1,
        s.id_type2,
        tr1.type as trans1,
        tr2.type as trans2
       FROM eqm_equipment_tbl eq
         JOIN eqm_area_tbl eqa ON eqa.code_eqp = eq.id
         LEFT JOIN adv_address_tbl a ON eq.id_addres = a.id
         JOIN eqm_compens_station_tbl s ON eq.id = s.code_eqp
         LEFT JOIN eqk_voltage_tbl v ON s.id_voltage = v.id
         LEFT JOIN eqi_compensator_tbl tr1 ON s.id_type1 = tr1.id
         LEFT JOIN eqi_compensator_tbl tr2 ON s.id_type2 = tr2.id
         LEFT JOIN ( SELECT eqm_compens_station_inst_tbl.code_eqp_inst,
                count(*)::integer AS eqp_cnt
               FROM eqm_compens_station_inst_tbl
              GROUP BY eqm_compens_station_inst_tbl.code_eqp_inst
              ORDER BY eqm_compens_station_inst_tbl.code_eqp_inst) u ON eq.id = u.code_eqp_inst
      WHERE eq.type_eqp = 8 and eqa.id_client=2062
      ORDER BY eq.name_eqp";

        if(Yii::$app->user->identity->id_res==1)
            Yii::$app->db_1->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==2)
            Yii::$app->db_2->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==3)
            Yii::$app->db_3->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==4)
            Yii::$app->db_4->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==5)
            Yii::$app->db_5->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==6)
            Yii::$app->db_6->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==7)
            Yii::$app->db_7->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==8)
            Yii::$app->db_8->createCommand($sql)->execute();

        $model = new view_photo_data();

        if ($model->load(Yii::$app->request->post()))
        {

            if(Yii::$app->user->identity->id_res<>5000)
                $model->id_res=Yii::$app->user->identity->id_res;
            return $this->redirect([ 'viewphoto','id_res' => $model->id_res,
                'id_tp' => $model->id_tp,'descr'=> $model->description,
                'date'=> $model->date,'priz_description'=>$model->priz_description,
                'folder'=> $model->folder]);}

        else {
            return $this->render('view_photo', [
                'model' => $model,
            ]);
        }

    }
    
    // Загрузка фото
    public function actionUpload()
    {
        $data = new sprav_tp();
//        Создаем вид на сервере PostGres, откуда берем список подстанций
        $sql = "CREATE OR REPLACE VIEW vw_sprav_tp as SELECT eq.id,
        eq.name_eqp,
        eq.num_eqp,
        v.voltage_min as name_v,
        eq.dt_install,
        eq.id_addres,
        eqa.id_client,
        a.adr::character varying AS adr,
        u.eqp_cnt,
        s.power,
        s.comp_cnt,
        s.p_regday,
        s.date_regday,
        s.id_type1,
        s.id_type2,
        tr1.type as trans1,
        tr2.type as trans2
       FROM eqm_equipment_tbl eq
         JOIN eqm_area_tbl eqa ON eqa.code_eqp = eq.id
         LEFT JOIN adv_address_tbl a ON eq.id_addres = a.id
         JOIN eqm_compens_station_tbl s ON eq.id = s.code_eqp
         LEFT JOIN eqk_voltage_tbl v ON s.id_voltage = v.id
         LEFT JOIN eqi_compensator_tbl tr1 ON s.id_type1 = tr1.id
         LEFT JOIN eqi_compensator_tbl tr2 ON s.id_type2 = tr2.id
         LEFT JOIN ( SELECT eqm_compens_station_inst_tbl.code_eqp_inst,
                count(*)::integer AS eqp_cnt
               FROM eqm_compens_station_inst_tbl
              GROUP BY eqm_compens_station_inst_tbl.code_eqp_inst
              ORDER BY eqm_compens_station_inst_tbl.code_eqp_inst) u ON eq.id = u.code_eqp_inst
      WHERE eq.type_eqp = 8 and eqa.id_client=2062
      ORDER BY eq.name_eqp";
        if(Yii::$app->user->identity->id_res==1)
            Yii::$app->db_1->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==2)
            Yii::$app->db_2->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==3)
            Yii::$app->db_3->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==4)
            Yii::$app->db_4->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==5)
            Yii::$app->db_5->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==6)
            Yii::$app->db_6->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==7)
            Yii::$app->db_7->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==8)
            Yii::$app->db_8->createCommand($sql)->execute();


        $model = new upload();
//        debug(Yii::$app->user->identity->role );
//        die;
        
        if ($model->load(Yii::$app->request->post()) )
        {

                if(Yii::$app->user->identity->id_res<>5000)
                 $model->id_res=Yii::$app->user->identity->id_res;

                if(empty($model->folder) || is_null($model->folder)) $model->folder='Загальна';

             $model->save();

            //$model->image = UploadedFile::getInstance($model,'image');  //  Загрузка одной фотографии
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles'); // Зазрузка нескольких фото
            //if($model->image) $model->upload();   //  Загрузка одной фотографии
            $model->upload();// Зазрузка нескольких фото

            if(empty( $model->imageFiles))
            {
                Yii::$app->session->setFlash('Error',"Фото не вибрано");
                return $this->refresh();
            }

            //Запись подстанции на сервер MySQL
            $data = spr_tp_mysql::find()->where(['id'=>$model->id_tp])->andwhere(['id_res'=>$model->id_res])->all();
            $pg = Spr_tp::find()->where(['id'=>$model->id_tp])->one();
            $nazv_sub = $pg->name_eqp;
            if(empty($data))
            {   
                $data = new spr_tp_mysql();
                $data->id = $model->id_tp;
                $data->id_res = $model->id_res;
                $data->nazv = $nazv_sub;
                $data->save();
                
            }
            Yii::$app->session->setFlash('success',"Фото $model->image завантажено");
            return $this->refresh();

        }
        else {
            
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

    // Просмотр фото (загрузка исходных данных для поиска в режиме администратора)
    public function actionViewphoto_main($id_res)
    {
        Yii::$app->user->identity->id_res = $id_res;
        
        $model = new view_photo_data();

        if ($model->load(Yii::$app->request->post())) {
            $model->id_res = $id_res;
            $session = Yii::$app->session;
            $session->open();
            $session->set('id_res', $id_res);
            return $this->redirect(['viewphoto', 'id_res' => $model->id_res,
                'id_tp' => $model->id_tp, 'descr' => $model->description,
                'date' => $model->date,'date2' => $model->date2,
                'priz_description'=>$model->priz_description,
                'folder'=>$model->folder]);
        }
        else
        {
            return $this->render('view_photo', [
                'model' => $model,
            ]);
        }
    }
    // Просмотр фото (загрузка исходных данных для поиска)
    public function actionView_photo()
    {
        $data = new sprav_tp();
//        Создаем вид на сервере PostGres, откуда берем список подстанций
        $sql = "CREATE OR REPLACE VIEW vw_sprav_tp as SELECT eq.id,
        eq.name_eqp,
        eq.num_eqp,
        v.voltage_min as name_v,
        eq.dt_install,
        eq.id_addres,
        eqa.id_client,
        a.adr::character varying AS adr,
        u.eqp_cnt,
        s.power,
        s.comp_cnt,
        s.p_regday,
        s.date_regday,
        s.id_type1,
        s.id_type2,
        tr1.type as trans1,
        tr2.type as trans2
       FROM eqm_equipment_tbl eq
         JOIN eqm_area_tbl eqa ON eqa.code_eqp = eq.id
         LEFT JOIN adv_address_tbl a ON eq.id_addres = a.id
         JOIN eqm_compens_station_tbl s ON eq.id = s.code_eqp
         LEFT JOIN eqk_voltage_tbl v ON s.id_voltage = v.id
         LEFT JOIN eqi_compensator_tbl tr1 ON s.id_type1 = tr1.id
         LEFT JOIN eqi_compensator_tbl tr2 ON s.id_type2 = tr2.id
         LEFT JOIN ( SELECT eqm_compens_station_inst_tbl.code_eqp_inst,
                count(*)::integer AS eqp_cnt
               FROM eqm_compens_station_inst_tbl
              GROUP BY eqm_compens_station_inst_tbl.code_eqp_inst
              ORDER BY eqm_compens_station_inst_tbl.code_eqp_inst) u ON eq.id = u.code_eqp_inst
      WHERE eq.type_eqp = 8 and eqa.id_client=2062
      ORDER BY eq.name_eqp";

        if(Yii::$app->user->identity->id_res==1)
            Yii::$app->db_1->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==2)
            Yii::$app->db_2->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==3)
            Yii::$app->db_3->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==4)
            Yii::$app->db_4->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==5)
            Yii::$app->db_5->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==6)
            Yii::$app->db_6->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==7)
            Yii::$app->db_7->createCommand($sql)->execute();
        if(Yii::$app->user->identity->id_res==8)
            Yii::$app->db_8->createCommand($sql)->execute();

        $model = new view_photo_data();
        
        if ($model->load(Yii::$app->request->post()))
        {

            if(Yii::$app->user->identity->id_res<>5000)
            $model->id_res=Yii::$app->user->identity->id_res;

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
            
            return $this->redirect([ 'viewphoto','id_res' => $model->id_res,
                'id_tp' => $model->id_tp,'descr'=> $model->description,
                'date'=> $model->date,'priz_description'=>$model->priz_description,
                'folder'=> $model->folder]);}

        else {
            return $this->render('view_photo', [
                'model' => $model,
            ]);
        }
    }
    
    // Просмотр отдельного фото 
    public function actionDetail_photo($id,$file_path)
    {
            $data = view_photo::find()->where(['id'=>$id])->one();
            //$img = $data->file_path;
            $img = $file_path;
            $descr = $data->description;
            $date  = $data->date;
            $id_tp  = $data->id_tp;
            $nazv_tp = $data->nazv_tp;
//            debug($img);
//            return;

            return $this->render('detail_photo', ['img' => $img,'descr'=>$descr,
                'date'=>$date,'nazv_tp'=>$nazv_tp,'id' => $id,'id_tp' => $id_tp,'file_path'=> $img]);
       
    }


    // Редактирование описания фото
    public function actionEdit_img($id,$img)
    {
        $data = view_photo::find()->where(['id'=>$id])->one();
        //$img = $data->file_path;
        $descr = $data->description;
        $date  = $data->date;
        $nazv_tp = $data->nazv_tp;
        $id_tp  = $data->id_tp;

        return $this->redirect([ 'editimg','img' => $img,'descr'=>$descr,
            'date'=>$date,'nazv_tp'=>$nazv_tp,'id' => $id,'id_tp' => $id_tp]);

    }

    // Редактирование описания фото
    public function actionEditimg($img,$descr,$date,$nazv_tp,$id,$id_tp)
    {
        $model = new cphoto();
        if ($model->load(Yii::$app->request->post())) {
            $data = edit_photo::find()->where(['id'=>$id])->one();
            $data->description = $model->description;

            $data->save();
            return $this->render('detail_photo', ['img' => $img,'descr'=>$model->description,
                'id_tp' => $id_tp,'date'=>$date,'nazv_tp'=>$nazv_tp,'id' => $id,'file_path'=> $img]);
        }
        else
        return $this->render('edit_img',['model'=>$model,'img' => $img,'descr'=>$descr,
            'date'=>$date,'nazv_tp'=>$nazv_tp,'id' => $id]);

    }

    // Удаление фото
    public function actionDel_img($id,$file_path)
    {
        //$data = edit_photo::find()->where(['id'=>$id])->one();
        $data_c = image::find()->where(['item_id'=>$id])->all();
        $kol=count($data_c);
        if($kol==1){
            $data = edit_photo::find()->where(['id'=>$id])->one();
            $data->delete();
        }
        $data_i = image::find()->where(['item_id'=>$id])->andwhere(['file_path'=>$file_path])->one();

        $data_i->delete();
        // Удаление ссылки на файл фото
//        $data = image::find()->where(['item_id'=>$id])->one();
//        $file = $data->file_path;
//        $data->delete();
        // Удаление файла фото
        unlink('store/'. $file_path);
        // Удаление папки
        if($kol==1)
        rmdir('store/'.'Uploads/Upload'.$id);
        return $this->redirect([ 'view_photo']);
      }

    // Просмотр фото
    public function actionViewphoto($id_res,$id_tp,$descr,$date,$date2=null,$priz_description,$folder)
    {
        $PageTotal = 6;  // Количество фото на странице
        //Генерация поискового sql выражения
        $sql = 'select * from vw_photo where id_res='.$id_res;
        $z='';

        if(empty($folder)) {
            if (!$priz_description) {
                //        Если пустая подстанция
                if (empty($id_tp) || $id_tp < 0) {
                    if (empty($date)) {   // Если пустая дата
                        if (empty($descr)) {

                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                    ->andwhere(['like', 'description', "$descr"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }

                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' . "'" . $date . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . "'" . ' and date<=' . "'" . $date2 . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date>=' . "'" . $date . "'" .
                                    ' and date<=' . "'" . $date2 . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                } else {
                    // Если пустая дата

                    if (empty($date)) {
                        if (empty($descr)) {
                            $z = $z . ' and id_tp=' . $id_tp;
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and id_tp=' . $id_tp;
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['like', 'description', "$descr"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }
                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' .
                                    "'" . $date . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . "'" . ' and date<=' . "'" . $date2 . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' .
                                    "'" . $date . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                }
            }

//      Если не пустое описание
            if ($priz_description) {
                //        Если пустая подстанция
                if (empty($id_tp) || $id_tp < 0) {
                    if (empty($date)) {   // Если пустая дата
                        if (empty($descr)) {
                            $z = $z . ' and description<>' . "''";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['<>', 'length(description)', 0]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                    ->andwhere(['like', 'description', "$descr"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }

                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'" . ' and description<>' . "''";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['<>', 'length(description)', 0]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' . "'" . $date . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . ' and date<=' . "'" . $date2 . "'" . ' and description<>' . "''";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['<>', 'length(description)', 0]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date>=' . "'" . $date . "'"
                                    . ' and date<=' . "'" . $date2;
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                } else {
                    // Если пустая дата
                    if (empty($date)) {
                        if (empty($descr)) {
                            $z = $z . ' and id_tp=' . $id_tp . ' and description<>' . "''";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['<>', 'length(description)', 0]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and id_tp=' . $id_tp;
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['like', 'description', "$descr"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }

                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'" . ' and id_tp=' . $id_tp . ' and description<>' . "''";

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp])->andwhere(['<>', 'length(description)', 0]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' .
                                    "'" . $date . "'" . ' and id_tp=' . $id_tp;

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . ' and date<=' . "'" . $date2 .
                                    "'" . ' and id_tp=' . $id_tp . ' and description<>' . "''";

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp])->andwhere(['<>', 'length(description)', 0]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date>=' .
                                    "'" . $date . ' and date<=' . "'" . $date2 . "'" . ' and id_tp=' . $id_tp;

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                }
            }
        }

       // Если не пустая папка
        if(!empty($folder)) {
            if (!$priz_description) {
                //        Если пустая подстанция
                if (empty($id_tp) || $id_tp < 0) {
                    if (empty($date)) {   // Если пустая дата
                        if (empty($descr)) {

                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                    ->andwhere(['like', 'description', "$descr"])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }

                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' . "'" . $date . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . "'" . ' and date<=' . "'" . $date2 . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date>=' . "'" . $date . "'" .
                                    ' and date<=' . "'" . $date2 . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                } else {
                    // Если пустая дата

                    if (empty($date)) {
                        if (empty($descr)) {
                            $z = $z . ' and id_tp=' . $id_tp;
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and id_tp=' . $id_tp;
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['like', 'description', "$descr"])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }
                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' .
                                    "'" . $date . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . "'" . ' and date<=' . "'" . $date2 . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' .
                                    "'" . $date . "'" . ' and id_tp=' . $id_tp;
                                $data = view_photo::find()->where(['id_res' => $id_res])->all();
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                }
            }

//      Если не пустое описание
            if ($priz_description) {
                //        Если пустая подстанция
                if (empty($id_tp) || $id_tp < 0) {
                    if (empty($date)) {   // Если пустая дата
                        if (empty($descr)) {
                            $z = $z . ' and description<>' . "''";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                    ->andwhere(['<>', 'length(description)', 0])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                    ->andwhere(['like', 'description', "$descr"])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }

                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'" . ' and description<>' . "''";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['<>', 'length(description)', 0])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' . "'" . $date . "'";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . ' and date<=' . "'" . $date2 . "'" . ' and description<>' . "''";
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['<>', 'length(description)', 0])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date>=' . "'" . $date . "'"
                                    . ' and date<=' . "'" . $date2;
                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                } else {
                    // Если пустая дата
                    if (empty($date)) {
                        if (empty($descr)) {
                            $z = $z . ' and id_tp=' . $id_tp . ' and description<>' . "''";
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['<>', 'length(description)', 0])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        } else {
                            $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and id_tp=' . $id_tp;
                            $dataProvider = new ActiveDataProvider([
                                'query' => view_photo::find()->where(['id_res' => $id_res])
                                    ->andwhere(['id_tp' => $id_tp])
                                    ->andwhere(['like', 'description', "$descr"])
                                    ->andwhere(['=', 'folder', "$folder"]),
                                'pagination' => [
                                    'pageSize' => $PageTotal,
                                ],
                            ]);
                        }

                    } else {
                        // Если не пустая дата
                        if (empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date=' . "'" . $date . "'" . ' and id_tp=' . $id_tp . ' and description<>' . "''";

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['<>', 'length(description)', 0])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date=' .
                                    "'" . $date . "'" . ' and id_tp=' . $id_tp;

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->andwhere(['date' => $date])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }
                        if (!empty($date2)) {
                            if (empty($descr)) {
                                $z = $z . ' and date>=' . "'" . $date . ' and date<=' . "'" . $date2 .
                                    "'" . ' and id_tp=' . $id_tp . ' and description<>' . "''";

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])->
                                    andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['<>', 'length(description)', 0])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            } else {
                                $z = $z . ' and description like' . "'%" . $descr . "%'" . ' and date>=' .
                                    "'" . $date . ' and date<=' . "'" . $date2 . "'" . ' and id_tp=' . $id_tp;

                                $dataProvider = new ActiveDataProvider([
                                    'query' => view_photo::find()->where(['id_res' => $id_res])
                                        ->andwhere(['like', 'description', "$descr"])->
                                        andwhere(['between', 'date', $date, $date2])
                                        ->andwhere(['id_tp' => $id_tp])
                                        ->andwhere(['=', 'folder', "$folder"]),
                                    'pagination' => [
                                        'pageSize' => $PageTotal,
                                    ],
                                ]);
                            }
                        }

                    }
                }
            }
        }



        $sql = $sql . $z;
        if (!empty($folder)) {
            $sql = $sql . " and folder='$folder'";
        }

//        debug($sql);
//        die;

        $data = photo::findBySql($sql)->all();
//        $data_d = photo::findBySql($sql);
//        $dataProvider = new SDataProvider([
//            'query' => $data_d,
//            'pagination' => [
//                'pageSize' => 3,
//            ],
//
//         ]);
//
        //$dataProvider
        //die;

        if(empty($data[0]->id))
        {
            $model = new info();
            $model->title = 'Увага!';
            $model->info1 = "По введеним параметрам пошуку не найдено жодної фотографії";
            $model->style1 = "d15";
            $model->style_title = "d9";
            return $this->render('info', [
                'model' => $model]);
        }
        else
            return $this->render('viewphoto', [
                'dataProvider' => $dataProvider,'id_tp' => $id_tp, 'id_res' => $id_res, 'descr'=> $descr,
                'date'=> $date,'priz_description'=>$priz_description,'folder'=>$folder
            ]);

    }

    // Подгрузка подстанций - происходит при выборе РЄСа
    public function actionGettp($id)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
//            $tp = spr_tp::find()->select(['nazv'])->where('id_res=:id_res', [':id_res' => $id])->all();
//            $res_tp = $tp[0]->nazv;

            if (empty($res_tp))
                $sql = "Select concat(cast(id as char(3)),'  ',name_eqp) as nazv from spr_tp";
            else
                $sql = "Select concat(cast(id as char(3)),'  ',name_eqp) as nazv from spr_tp where id_res=$id";

            $tp = spr_tp::findBySql($sql)->all();

            return ['success' => true, 'nazv' => $tp];

        }
    }

    // Подгрузка подстанций - происходит при наборе первых букв подстанции
    // происходит при загрузке фото
    public function actionGet_search_tp($name)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $name1 = mb_strtolower($name,"UTF-8");
        $name2 = mb_strtoupper($name,"UTF-8");
        if (Yii::$app->request->isAjax) {
            $tp = spr_tp::find()->select(['id','name_eqp'])->where(['like','name_eqp',"$name1"])->
            orwhere(['like','name_eqp',"$name"])->all();

//            $sql = "select concat(cast(id as char(6)) , '  ' , name_eqp) as nazv from vw_sprav_tp
//                    where name_eqp like "."'"."$name%'";

//            $tp = spr_tp::findBySql($sql)->all();

            return ['success' => true, 'tp' => $tp];

        }
    }

    // Подгрузка координат подстанций 
    public function actionGet_tp($name)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
       
        if (Yii::$app->request->isAjax) {
//            $tp = spr_tp::find()->select(['id','name_eqp'])->where(['like','name_eqp',"$name"])->all();
//            if(empty($tp)) return ['success' => true, 'lat' => '','lon' => ''];
//            $id = $tp[0]->id;
            $geo = geo_tp::find()->select(['lat','lon'])->where('id_tp=:id',[':id' => $name])->all();
            if(isset($geo[0]->lat))
                $lat = $geo[0]->lat;
            else
                $lat = '';
            
            if(isset($geo[0]->lon))
                $lon = $geo[0]->lon;
            else
                $lon = '';
            
            return ['success' => true, 'lat' => $lat,'lon' => $lon];

        }
    }
    // Подгрузка подстанций - при просмотре фото(выборка только тех
//        подстанций, где есть фотографии)
        public function actionGettp_exists($id)
        {

            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isAjax) {
//                $tp = view_photo::find()->select(['nazv_tp'])->where('id_res=:id_res',[':id_res' => $id])->all();
//                $res_tp = $tp[0]->nazv_tp;
                $cnt = view_photo::find()->where('id_res=:id_res',[':id_res' => $id])->count();
                $count = $cnt;

//                if(empty($res_tp))
//                    $sql = "Select concat(cast(id as char(6)),'  ',nazv) as nazv from spr_tp
//                    union Select '999999    Всі підстанції   (".$count.")'". " as nazv order by nazv desc";
//                else
                    $sql = "Select concat(cast(id_tp as char(6)),'  ',nazv_tp,'    (',count(id_tp),')') as nazv
                    from vw_photo where id_res=$id
                    group by id_tp,nazv_tp  
                    union Select '-99999    Всього фото    (".$count.")'". " as nazv order by substr(nazv,7) ";

                $tp = spr_tp_mysql::findBySql($sql)->all();

                return ['success' => true, 'nazv' => $tp];

            }

    return ['oh no' => 'you are not allowed :('];
    }

     // Определяем расстояние по дороге от базы до объекта - происходит при нажатии на карту
     public function actionGetdist($url,$origins,$destinations) {
          
    Yii::$app->response->format = Response::FORMAT_JSON;
    if (Yii::$app->request->isAjax) {
        
        $destinations = str_replace(' ', '', $destinations);
        $url = $url . '&origins='.$origins.'&destinations='.$destinations;
        $url = $url . '&language=ru&region=UA';
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url ); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $output = curl_exec($ch); 
        curl_close($ch);   
        $output = json_decode($output,true);
      
        return ['success' => true, 'output' => $output];
    }
    }



    // Определяем гео-координаты выбранного РЭСа 
    public function actionGetres($id) {
        
    Yii::$app->response->format = Response::FORMAT_JSON;
    if (Yii::$app->request->isAjax) {
        /*
         * Поля, извлекаемые из таблицы справочника РЭСов:
         * geo_koord - гео-координаты РЭСа
         * geo_fromwhere_sd - гео-координаты места откуда едет машина (лаборатория)
         * geo_fromwhere_sz - гео-координаты места откуда едет машина (устан. приборов учета и технич. проверка)
         * town_fromwhere_sd - город откуда едет машина (лаборатория)
         * town_fromwhere_sz - город откуда едет машина (устан. приборов учета и технич. проверка)
         * */
        $model = spr_res::find()->select(['geo_koord','geo_fromwhere_sd',
            'geo_fromwhere_sz','town_fromwhere_sd','town_fromwhere_sz'])
            ->where('id=:id',[':id' => $id])->all();
        $geo_koord = $model[0]->geo_koord;
        $n = strpos($geo_koord, ',');
        $lat = substr($geo_koord,0,$n);
        $lng = substr($geo_koord,$n+1);

        $geo_sd = $model[0]->geo_fromwhere_sd;
        $n = strpos($geo_sd, ',');
        $lat_sd = substr($geo_sd,0,$n);
        $lng_sd = substr($geo_sd,$n+1);

        $geo_sz = $model[0]->geo_fromwhere_sz;
        $n = strpos($geo_sz, ',');
        $lat_sz = substr($geo_sz,0,$n);
        $lng_sz = substr($geo_sz,$n+1);

        $town_sd = $model[0]->town_fromwhere_sd;
        $town_sz = $model[0]->town_fromwhere_sz;

        return ['success' => true, 'geo_koord' => $geo_koord,'lat' => $lat,'lng' => $lng,
            'lat_sd' => $lat_sd,'lng_sd' => $lng_sd,
            'lat_sz' => $lat_sz,'lng_sz' => $lng_sz,
            'town_sd' => $town_sd,'town_sz' => $town_sz,
            'id' => $id];
    }
    return ['oh no' => 'you are not allowed :('];
    }
    

//    Страница о программе
    public function actionAbout()
    {
        $model = new info();
        $model->title = 'Про програму';
        $model->info1 = "Ця програма здійснює перегляд та завантаження фотографій підстанцій";
        $model->style1 = "d15";
        $model->style_title = "d9";
        return $this->render('info', [
            'model' => $model]);

//        return $this->render('about');
    }

    // Определяем гео-координаты выбранной подстанции
    public function actionGetgeo($id,$nazv) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {

            $model = geo_tp::find()->select(['lat','lon'])
                ->where('id=:id',[':id' => $id])->andwhere('nazv=:nazv',[':nazv' => $nazv])->all();
            $lat = $model[0]->lat;
            $lng = $model[0]->lng;

            return ['success' => true, 'lat' => $lat,'lng' => $lng];
        }
        return ['oh no' => 'you are not allowed :('];
    }


// Добавление новых пользователей
    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'user8'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'user8';
            $user->email = 'krr@i.ua';
            $user->id_res = 8;
            $user->setPassword('Cjrhjdbot');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'good';
            }
        }  
    }
// Выход пользователя
    public function actionLogout()
    {
        $session = Yii::$app->session;
        if ($session->isActive)
        $session->close();

        Yii::$app->user->logout();

        return $this->goHome();
    }
}
