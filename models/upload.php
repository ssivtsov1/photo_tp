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
use yii\web\UploadedFile;

class Upload extends \yii\db\ActiveRecord
{
    public $image;
    public $imageFiles;
    public $search_tp;
    public $is_folder;

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
            'image' => 'Виберіть фото',
            'imageFiles' => 'Виберіть фото',
            'search_tp'=>'Пошук підстанції',
            'is_folder'=>'Існуюча папка',
            'folder'=>'Нова папка'
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
           [['id','description','photo','id_tp','id_res','search_tp','folder','is_folder'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            //[['image'],'file','extensions'=>'png,jpg'],
            [['imageFiles'], 'file',  'extensions' => 'png, jpg', 'maxFiles' => 0,'checkExtensionByMimeType'=>false],

            [['id_res'], 'default', 'value'=>Yii::$app->user->identity->id_res],

        ];
   }
// 'checkExtensionByMimeType'=>false
    public function upload()
    {
        if($this->validate()){
             // Загрузка одной фотографии
//            $path = "store/".$this->image->basename.'.'.$this->image->extension;
//            $this->image->saveas($path);
//            $this->attachImage($path);
//            @unlink($path);

            // Зазрузка нескольких фото
            foreach ($this->imageFiles as $file) {
                //$filename=Yii::$app->getSecurity()->generateRandomString(15);
               // $path = "store/".$file->baseName.'.'.$file->baseName;
                $path = "store/".$file->baseName.'.'.$file->extension;
                $file->saveAs($path);
                $this->attachImage($path);
                @unlink($path);
            }
            return true;
        }
        else
            return false;
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


