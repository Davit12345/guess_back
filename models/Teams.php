<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teams".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $country_id
 * @property string|null $flag
 */
class Teams extends \yii\db\ActiveRecord
{
    public $flag;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['flag'], 'safe'],

        ];
    }
    public function afterFind()
    {
        parent::afterFind();


        $this->flag ='ss';

    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'country_id' => Yii::t('app', 'Country ID'),
            'flag' => Yii::t('app', 'Flag'),
        ];
    }
}
