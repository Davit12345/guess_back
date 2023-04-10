<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_league".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $league_id
 * @property int|null $status
 * @property float|null $points
 * @property float|null $points_coeff
 * @property string|null $update_ts
 * @property string|null $create_ts
 */
class UserLeague extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE=1;
    const STATUS_DELETED=10;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_league';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'league_id', 'status'], 'integer'],
            [['league_id'], 'required'],
            [['points','points_coeff'], 'number'],
            [['update_ts', 'create_ts'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'league_id' => Yii::t('app', 'League ID'),
            'status' => Yii::t('app', 'Status'),
            'points' => Yii::t('app', 'Points'),
            'update_ts' => Yii::t('app', 'Update Ts'),
            'create_ts' => Yii::t('app', 'Create Ts'),
        ];
    }
}
