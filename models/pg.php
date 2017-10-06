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


class Pg extends \yii\db\ActiveRecord
{

   
    public static function tableName()
    {
//        Используется вид на SQL сервере
        return 'pg_class';
    }

    public function attributeLabels()
    {
        return [
            'relname' => 'relname',


        ];
    }

    public function rules()
    {
        return [

            [['relname'], 'safe'],
        ];
    }


    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        return Yii::$app->get('db_g');
    }

}


