<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "league_game".
 *
 * @property int $int
 * @property int|null $league_id
 * @property int|null $game_id
 * @property int|null $gw_id
 */
class LeagueGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'league_game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['league_id', 'game_id', 'gw_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'int' => Yii::t('app', 'Int'),
            'league_id' => Yii::t('app', 'League ID'),
            'game_id' => Yii::t('app', 'Game ID'),
            'gw_id' => Yii::t('app', 'Gw ID'),
        ];
    }
}
