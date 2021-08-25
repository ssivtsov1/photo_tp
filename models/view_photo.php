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


class View_photo extends \yii\db\ActiveRecord
{
    public $image;

    public function behaviors(){
        return [
            'image' => [
                'class' => 'circulon\images\behaviors\ImageBehavior',
                'idAttribute' => 'id' // set the models id column , default : 'id'
            ]
        ];
    }

    public static function tableName()
    {
        return 'vw_photo';
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
        if(Yii::$app->user->identity->id_res<>5000)
        return [
            [['id','description','photo','id_tp','id_res',
            'file_path','is_main','url_alias'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['id_res'],'required'],
            [['image'],'file','extensions'=>'png,jpg'],
            [['id_res'], 'default', 'value'=>Yii::$app->user->identity->id_res],

        ];
        if(Yii::$app->user->identity->id_res==5000)
        return [
            [['id','description','photo','id_tp','id_res',
            'file_path','is_main','url_alias'], 'safe'],
            [['id_res'],'required'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['image'],'file','extensions'=>'png,jpg'],
            

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


