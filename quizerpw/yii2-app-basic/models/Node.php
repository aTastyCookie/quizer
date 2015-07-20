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
            [['name', 'quest_id'], 'required'],
            [['quest_id', 'next', 'prev', 'prev2', 'top', 'left', 'case_depend'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['answer'], 'string']
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
            'quest_id' => Yii::t('app', 'Quest ID'),
			'question' => 'Вопрос',
			'answer' => 'Ответ',
			'case_depend' => 'Регистро-зависимый ответ'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest()
    {
        return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }

	public static function cleanConnections( $questId ) 
	{
		 $query = Node::find();
		 $query->andFilterWhere([
				'quest_id' => $questId
		  ]);
		 $dataProvider = new ActiveDataProvider([
            'query' => $query,
         ]);
		 $nodes = $dataProvider->getModels();
		 foreach ($nodes as $node) {
				$node->prev2 = null;
				$node->prev = null;
				$node->next = null;
				$node->save();
		 }
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
