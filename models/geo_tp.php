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
        if (isset(Yii::$app->user->identity->role))
            return Yii::$app->get('db');
        else
            return Yii::$app->get('db');
    }

}


