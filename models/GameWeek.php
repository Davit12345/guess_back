<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_week".
 *
 * @property int $id
 * @property int|null $league_id
 * @property int|null $gw
 * @property int|null $count_game
 * @property int|null $finished
 * @property int|null $suspended
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $name
 * @property int|null $creator_id
 */
class GameWeek extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_week';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['league_id', 'gw', 'count_game', 'finished', 'suspended', 'creator_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'league_id' => Yii::t('app', 'League ID'),
            'gw' => Yii::t('app', 'Gw'),
            'count_game' => Yii::t('app', 'Count Game'),
            'finished' => Yii::t('app', 'Finished'),
            'suspended' => Yii::t('app', 'Suspended'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'creator_id' => Yii::t('app', 'Creator ID'),
        ];
    }
}
