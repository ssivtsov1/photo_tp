<?php
// Гео-координаты подстанций
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


class Geo_tp extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'geo_tp';
    }
   
    public function rules()
    {
        return [
            [['id','lat','lon','id_tp','id_res','nazv'], 'safe'],
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


