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
class QuestRun extends \yii\db\ActiveRecord {

    const STATUS_ANSWERING = 'answering';
    const STATUS_CHOOSING = 'choosing';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quests_runs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quest_id', 'node_id', 'user_id'], 'required'],
            [['quest_id', 'node_id', 'user_id'], 'integer'],
            [['next_nodes'], 'string']
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

    public function getAnswersStatistics() {
        $run_answers = NodeAnswer::find()->where(['run_id' => $this->run_id])->all();
        $counts = [
            'right' => 0,
            'wrong' => 0
        ];

        foreach($run_answers as $answer) {
            if($answer->status)
                $counts['right']++;
            else
                $counts['wrong']++;
        }

        return $counts;
    }
}
