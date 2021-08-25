<?php
/**
 * Created by PhpStorm.
 * User: ssivtsov
 * Date: 21.06.2017
 * Time: 9:49
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;


class Spr_res extends \yii\db\ActiveRecord
{
    public $nazva;

    public static function tableName()
    {
        return 'spr_res';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nazv' => 'Назва РЕМ',
            'nazva' => 'Назва РЕМ',
            'addr' => 'Адреса РЕМ',
            'geo_koord' => 'Гео-координати',
            'tel' => 'Телефон',
            'shortname' => 'Коротка назва'
        ];
    }

    public function rules()
    {
        return [

            [['nazv', 'nazva','id', 'addr', 'tel'], 'required'],
            [['geo_fromwhere_sd','geo_fromwhere_sz',
                'town_fromwhere_sd','town_fromwhere_sz',
                'geo_koord','shortname'],'safe'],
           
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
