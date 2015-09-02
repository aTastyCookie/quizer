<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\Cookie;

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

    public function getQuest() {
        return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }

    public function getUser() {
        return $this->hasOne(ARUser::className(), ['id' => 'user_id']);
    }

    public function updateCurrentRun(&$quest, &$current_node, &$answer, $is_penalty) {
        if($this->status == self::STATUS_CHOOSING) {
            $next_nodes = json_decode($this->next_nodes);

            if(!empty($next_nodes)) {
                $current_node = Node::find()->where('id IN (' . implode(', ', json_decode($this->next_nodes)) . ')')->all();

                if (count($current_node) == 1) {
                    $current_node = $current_node[0];
                    $answer->quest_id = $current_node->quest_id;
                    $answer->node_id = $current_node->id;
                }
            } else {
                $current_node = Node::findOne($this->node_id);
                $answer = null;
                $this->time_end = time();
                $this->is_complete = true;
                $this->save(false);


                if(!$is_penalty && Yii::$app->getRequest()->getCookies()->has('run-quest-'.$quest->id))
                    Yii::$app->getResponse()->getCookies()->remove('run-quest-'.$quest->id);

                // Listener event on quest end (for achievements)
                Achievement::listenerEventOnQuestEnd($current_run, $quest, $answer);
            }
        } elseif($this->status == self::STATUS_ANSWERING) {
            if(!Yii::$app->getRequest()->getCookies()->has('run-quest-'.$quest->id)) {
                Yii::$app->getResponse()->getCookies()->add(new Cookie([
                    'name' => 'run-quest-'.$quest->id,
                    'value' => time(),
                    'expire' => time() + 60 * 60,
                ]));
            }

            $current_node = Node::find()->where(['id' => $this->node_id])->one();
            $answer->quest_id = $current_node->quest_id;
            $answer->node_id = $current_node->id;
        }
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
