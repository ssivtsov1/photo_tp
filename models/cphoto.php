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


class Cphoto extends Model
{
    public $id;
    public $description;



    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Опис фото:',

        ];
    }

    public function rules()
    {
           return [
           [['id','description'], 'safe'],

        ];
   }


    public function getId()
    {
        return $this->getPrimaryKey();
    }

}


