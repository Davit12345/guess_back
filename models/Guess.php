<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guess".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $game_id
 * @property string|null $choose
 * @property int|null $game_type
 * @property string|null $create_date
 * @property string|null $update_date
 * @property int|null $resulte
 */
class Guess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guess';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'game_id', 'game_type', 'resulte'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['choose'], 'string', 'max' => 255],
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
            'game_id' => Yii::t('app', 'Game ID'),
            'choose' => Yii::t('app', 'Choose'),
            'game_type' => Yii::t('app', 'Game Type'),
            'create_date' => Yii::t('app', 'Create Date'),
            'update_date' => Yii::t('app', 'Update Date'),
            'resulte' => Yii::t('app', 'Resulte'),
        ];
    }
}
