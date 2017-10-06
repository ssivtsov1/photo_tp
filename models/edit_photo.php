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


class Edit_photo extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'photo';
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Опис фото',
            'id_res' => 'РЕМ',
            'id_tp' => 'Підстанція',
            'date' => 'Дата',

        ];
    }

    public function rules()
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
           return [
           [['id_tp'],'required'],
           [['id','description','photo','id_tp','id_res'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['id_res'], 'default', 'value'=>Yii::$app->user->identity->id_res],

        ];
   }

   
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        return Yii::$app->get('db');
    }

}


