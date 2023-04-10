<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "all".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property string|null $code
 * @property int|null $flag
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type','flag'], 'integer'],
            [['name', 'code', ], 'string', 'max' => 255],
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
            'type' => Yii::t('app', 'Type'),
            'code' => Yii::t('app', 'Code'),
            'flag' => Yii::t('app', 'Flag'),
        ];
    }
}
