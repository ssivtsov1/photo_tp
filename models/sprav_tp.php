<?php
/**
 * Created by PhpStorm.
 * User: ssivtsov
 * Date: 03.07.2017
 * Time: 9:49
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


class Sprav_tp extends \yii\db\ActiveRecord
{

   public $equipment;

    public static function tableName()
    {
//        Используется вид на SQL сервере
        return 'eqm_equipment_tbl';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_eqp' => 'Назва підстанції',
            'id_addres' => 'Розположення',

        ];
    }

    public function rules()
    {
        return [

            [['name_eqp','id_addres'], 'safe'],
        ];
    }


    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        if(Yii::$app->user->identity->role == 3)
        {
            $session = Yii::$app->session;
            $session->open();
            if($session->has('id_res'))
            {
                Yii::$app->user->identity->id_res = $session->get('id_res');

            }
        }

        if(Yii::$app->user->identity->id_res==1)
            return Yii::$app->get('db_1');
        if(Yii::$app->user->identity->id_res==2)
            return Yii::$app->get('db_2');
        if(Yii::$app->user->identity->id_res==3)
            return Yii::$app->get('db_3');
        if(Yii::$app->user->identity->id_res==4)
            return Yii::$app->get('db_4');
        if(Yii::$app->user->identity->id_res==5)
            return Yii::$app->get('db_5');
        if(Yii::$app->user->identity->id_res==6)
            return Yii::$app->get('db_6');
        if(Yii::$app->user->identity->id_res==7)
            return Yii::$app->get('db_7');
        if(Yii::$app->user->identity->id_res==8)
            return Yii::$app->get('db_8');
    }

}


