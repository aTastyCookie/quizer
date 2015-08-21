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
class NodeConnection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nodes_connections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [/*
            [['name', 'quest_id', 'question', 'answer'], 'required'],
            [['quest_id', 'next', 'prev', 'prev2', 'top', 'left', 'case_depend', 'time'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['answer'], 'string']*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [/*
            'id' => Yii::t('app', 'ID'),
            'name' => 'Заголовок',
            'quest_id' => Yii::t('app', 'Quest ID'),
			'question' => 'Вопрос',
			'time' => 'Время на ответ',
			'answer' => 'Ответ',
			'case_depend' => 'Регистро-зависимый ответ'*/
        ];
    }

    public function getToNodes() {
        return $this->hasOne(Node::className(), ['id' => 'to_node_id']);
    }
}
