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
class NodeAnswer extends \yii\db\ActiveRecord
{
    public $is_wrong = false;
    public $captcha;

    private $_sleep_config = [1 => 2, 2 => 5, 3 => 10, 4 => 15];

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
            [['text'], 'string', 'max' => 250],

            [['captcha'], 'required', 'on' => 'captcha'],
            [['captcha'], 'captcha', 'on' => 'captcha'],
            [['captcha'], 'safe']
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
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Answer'),
            'captcha' => Yii::t('app', 'Check Code')
        ];
    }

    public function load($data, $formName = null) {
        if(parent::load($data, $formName)) {
            if(!empty($this->captcha))
                $this->scenario = 'captcha';

            return true;
        } else
            return false;
    }

    public function getNode() {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }

    public function checkAnswer(&$current_run) {
        if(!$this->is_wrong) {
            $next_nodes = NodeConnection::find()->where(['from_node_id' => $this->node_id])->all();
            $next_ids = [];

            foreach ($next_nodes as $node)
                $next_ids[] = $node->toNodes->id;

            $current_run->next_nodes = !empty($next_ids) ? json_encode($next_ids) : null;

            if(count($next_ids) > 1)
                $current_run->status = QuestRun::STATUS_CHOOSING;
            elseif(count($next_ids) == 1) {
                $current_run->status = QuestRun::STATUS_ANSWERING;
                $current_run->node_id = $next_ids[0];
            } else
                $current_run->status = QuestRun::STATUS_CHOOSING;

            $current_run->sleep = 0;
            $current_run->count_attempts = 0;
            $this->status = true;
        } else {
            $penalty_timer = 0;
            $current_run->count_attempts++;

            if($current_run->count_attempts && isset($this->_sleep_config[$current_run->count_attempts]))
                $penalty_timer = $this->_sleep_config[$current_run->count_attempts];
            else {
                $penalty_timer = $this->_sleep_config[count($this->_sleep_config)];
                $this->scenario = 'captcha';
            }

            $current_run->sleep = time() + $penalty_timer;
            $current_run->status = QuestRun::STATUS_ANSWERING;
            $this->addError('text', 'Вы ответили неверно. Вы сможете ответить снова через секунд: '.($penalty_timer));
            $this->status = false;
        }
    }

    public function calculateAnswerTime($quest) {
        $this->time = 0;

        if($begin_time = Yii::$app->getRequest()->getCookies()->getValue('run-quest-'.$quest->id)) {
            $answer_time = time() - $begin_time;
            Yii::$app->getResponse()->getCookies()->remove('run-quest-'.$quest->id);

            if($this->is_wrong)
                Yii::$app->getResponse()->getCookies()->add(new Cookie([
                    'name' => 'run-quest-'.$quest->id,
                    'value' => time(),
                    'expire' => time() + 60 * 60,
                ]));

            $this->time = $answer_time;
        }
    }
}
