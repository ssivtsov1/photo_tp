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


class View_photo_data extends Model  //\yii\db\ActiveRecord
{
    public $id;
    public $description;
    public $id_res;
    public $id_tp;
    public $date;
    public $date2;
    public $priz_description;
    public $is_folder;
    public $folder;

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Опис фото',
            'id_res' => 'РЕМ',
            'id_tp' => 'Підстанція',
            'date' => 'Дата з',
            'date2' => 'Дата до',
            '$priz_description' => 'Наявність опису',
            'is_folder' => 'Папка'

        ];
    }

    public function rules()
    {
        return [

            [['id','description','photo','id_tp','id_res','priz_description',
                'is_folder','date2','folder'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['id_res'],'required'],

        ];
    }
}


