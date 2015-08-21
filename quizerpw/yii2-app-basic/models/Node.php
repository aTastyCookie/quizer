<?php

namespace app\models;
use yii\data\ActiveDataProvider;
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
            [['name', 'number', 'quest_id', 'question', 'answer'], 'required'],
            [['quest_id', 'number', 'top', 'left', 'case_depend', 'time'], 'integer'],
            [['number'], 'unique', 'targetAttribute' => ['quest_id', 'number']],
            [['name', 'description'], 'string', 'max' => 500],
            [['answer'], 'string'],
            [['description', 'case_depend'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => 'Заголовок',
            'number' => Yii::t('app', 'Number'),
            'quest_id' => Yii::t('app', 'Quest ID'),
			'question' => 'Вопрос',
			'answer' => 'Ответ',
            'description' => Yii::t('app', 'Description'),
			'case_depend' => 'регистрозависимый ответ',
			'time' => 'Время на ответ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest()
    {
        return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }

	public static function cleanConnections($quest_id) {
        foreach(NodeConnection::find()->where(['quest_id' => $quest_id])->all() as $connection)
            $connection->delete();

		 return true;
	}

	public static function findModel($id)
    {
        if (($model = Node::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
}
