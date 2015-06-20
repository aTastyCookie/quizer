<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "node".
 *
 * @property integer $id
 * @property string $name
 * @property integer $quest_id
 *
 * @property Quest $quest
 */
class Node extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'quest_id'], 'required'],
            [['quest_id'], 'integer'],
            [['name'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'quest_id' => Yii::t('app', 'Quest ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest()
    {
        return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }
}
