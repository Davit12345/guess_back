<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leagues".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $year
 * @property string|null $country_name
 * @property int|null $country_id
 * @property int|null $type
 * @property int|null $status
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $creator_id
 * @property int|null $show_type
 * @property int|null $flag
 */
class Leagues extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE=1;
    const STATUS_DELETED=10;
    const STATUS_HIDE=9;
    const STATUS_PASSIVE=8;


    const TYPE_REGULAR_LEAGUE=1; //prinmer ligue,
    const TYPE_CUP_LEAGUE=2;  //chempionery ligue
    const TYPE_OTHER_LEAGUE=3; //frendly games

    const TYPE_H2H_LEAGUE=4;
    const TYPE_DAILY_LEAGUE=5;
    const TYPE_GENERAL_LEAGUE=6;

    const TYPE_USER_OWN_LEAGUE=7;
    const TYPE_USER_OWN_H2H_LEAGUE=8;




    const SHOW_TYPE_PUBLIC=1;

    const SHOW_TYPE_USER_PUBLIC=2;
    const SHOW_TYPE_USER_PRIVATE=3;




    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leagues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'country_id', 'type', 'status', 'creator_id', 'show_type'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name', 'country_name', 'flag'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'year' => Yii::t('app', 'Year'),
            'country_name' => Yii::t('app', 'Country Name'),
            'country_id' => Yii::t('app', 'Country ID'),
        ];
    }
}
