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
class NodeAnswer extends \yii\db\ActiveRecord
{
    public $is_wrong = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nodes_answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quest_id', 'node_id', 'user_id', 'text'], 'required'],
            [['quest_id', 'node_id', 'user_id'], 'integer'],
            [['text'], 'answer'],
            [['text'], 'string', 'max' => 250]
        ];
    }

    public function answer() {
        if(!$this->hasErrors()) {
            if(!($node = Node::findOne($this->node_id)))
                $this->addError('node_id', 'Вопрос не найден');
            elseif(($node->case_depend && ($node->answer != $this->text)) ||
                   (mb_strtolower($node->answer, 'utf-8') != mb_strtolower($this->text, 'utf-8'))) {
                $this->is_wrong = true;
                return true;
            } else
                return true;
        } else
            return true;
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
}
