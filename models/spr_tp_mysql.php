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


class Spr_tp_mysql extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'spr_tp';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nazv' => 'Назва підстанції',
            'id_res' => 'РЕМ',

        ];
    }

    public function rules()
    {
        return [
            [['nazv','id_res','id'], 'safe'],
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


