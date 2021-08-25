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

class Select_res extends Model
{
    public $id;
    public $id_res;

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_res' => 'РЕМ',
        ];
    }

    public function rules()
    {
        return [

            [['id','id_res'], 'safe'],
            [['id_res'],'required'],

        ];
    }
}


