<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "participant".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $game_id
 * @property string|null $result
 * @property int|null $points
 * @property int|null $team_id
 * @property float|null $coefficient
 * @property string|null $description
 */
class Participant extends \yii\db\ActiveRecord
{
    public $flag;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_id', 'points', 'team_id'], 'integer'],
            [['description', 'result'], 'string'],
            [['name', 'flag'], 'string', 'max' => 255],
            [['flag'], 'safe'],
            [['coefficient'], 'number'],

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
            'game_id' => Yii::t('app', 'Game ID'),
            'result' => Yii::t('app', 'Result'),
            'points' => Yii::t('app', 'Points'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
