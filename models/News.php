<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string|null $desc
 * @property string|null $text
 * @property string|null $create_at
 * @property string|null $start_show_date
 * @property string|null $end_show_date
 * @property string|null $file
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['create_at', 'start_show_date', 'end_show_date', 'file'], 'safe'],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'desc' => Yii::t('app', 'Desc'),
            'text' => Yii::t('app', 'Text'),
            'create_at' => Yii::t('app', 'Create At'),
            'start_show_date' => Yii::t('app', 'Start Show Date'),
            'end_show_date' => Yii::t('app', 'End Show Date'),
            'file' => Yii::t('app', 'File'),
        ];
    }
}
