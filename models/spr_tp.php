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


class Spr_tp extends \yii\db\ActiveRecord
{

   
    public static function tableName()
    {
//        Используется вид на Postgres SQL сервере
        return 'vw_sprav_tp';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_eqp' => 'Назва підстанції',
            'adr' => 'Розположення',
            'power' => 'Потужність, кВт',
            'name_v' => 'Напруга, кВ',
            'trans1' => 'Транформатор_1',
            'trans2' => 'Транформатор_2',
            'comp_cnt'  => 'Кількість трансф.',
        ];
    }

    public function rules()
    {
        return [

            [['name_eqp','adr','power','name_v','trans1','trans2','comp_cnt','eqp_cnt'], 'safe'],
            [['name_eqp'],'string']
        ];
    }

//   Метод, необходимый для поиска
     public function search($params)
    {
        $query = spr_tp::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,'pagination' => [
            'pageSize' => 15,],

//            'key' => function ($model) {
//                return [
//                    'id' => $model->id,
//
//                ];}
        ]);
        if (!($this->load($params) && $this->validate())) {
           
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name_eqp', $this->name_eqp]);
        $query->andFilterWhere(['=', 'name_v', $this->name_v]);
        $query->andFilterWhere(['=', 'power', $this->power]);
        $query->andFilterWhere(['=', 'comp_cnt', $this->comp_cnt]);
        $query->andFilterWhere(['like', 'adr', $this->adr]);
        $query->andFilterWhere(['like', 'trans1', $this->trans1]);
        $query->andFilterWhere(['like', 'trans2', $this->trans2]);

        return $dataProvider;
    }
    
//    public function getId()
//    {
//        return $this->getPrimaryKey();
//    }

    public static function getDb()
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

        if(Yii::$app->user->identity->id_res==1)
            return Yii::$app->get('db_1');
        if(Yii::$app->user->identity->id_res==2)
            return Yii::$app->get('db_2');
        if(Yii::$app->user->identity->id_res==3)
            return Yii::$app->get('db_3');
        if(Yii::$app->user->identity->id_res==4)
            return Yii::$app->get('db_4');
        if(Yii::$app->user->identity->id_res==5)
            return Yii::$app->get('db_5');
        if(Yii::$app->user->identity->id_res==6)
            return Yii::$app->get('db_6');
        if(Yii::$app->user->identity->id_res==7)
            return Yii::$app->get('db_7');
        if(Yii::$app->user->identity->id_res==8)
            return Yii::$app->get('db_8');
    }

}


