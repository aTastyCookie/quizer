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
class ARUser extends \yii\db\ActiveRecord {

    public $seconds;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /*return [
            [['quest_id', 'node_id', 'user_id'], 'required'],
            [['quest_id', 'node_id', 'user_id'], 'integer'],
            [['next_nodes'], 'string']
        ];*/
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

    public function getCompleteStatisticsByQuest($quest_id) {
        $user_runs = QuestRun::find()->where(['user_id' => $this->id, 'quest_id' => intval($quest_id)])->all();
        $counts = [
            'complete' => 0,
            'no' => 0
        ];

        foreach($user_runs as $run) {
            if($run->is_complete)
                $counts['complete']++;
            else
                $counts['no']++;
        }

        return $counts;
    }

    public function getAnswersStatisticsByQuest($quest_id) {
        $user_answers = NodeAnswer::find()->where(['user_id' => $this->id, 'quest_id' => intval($quest_id)])->all();
        $counts = [
            'right' => 0,
            'wrong' => 0
        ];

        foreach($user_answers as $answer) {
            if($answer->status)
                $counts['right']++;
            else
                $counts['wrong']++;
        }

        return $counts;
    }
/*
    public static function isAdmin() {
        $assign = \Yii::$app->getAuthManager()->getRolesByUser(\Yii::$app->getUser()->getId());

        if(isset($assign['admin']) && !empty($assign['admin']))
            return true;

        return false;
    }*/
}
