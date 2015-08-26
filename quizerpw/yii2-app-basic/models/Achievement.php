<?php

namespace app\models;
use Yii;
use yii\base\Exception;
use yii\helpers\Html;

/**
 * This is the model class for table "node".
 *
 * @property integer $id
 * @property string $name
 * @property integer $quest_id
 *
 * @property Quest $quest
 */
class Achievement extends \yii\db\ActiveRecord {

    const TYPE_UNIQUE = 'unique';
    const TYPE_ON_ANSWER = 'on_answer';
    const TYPE_ON_QUEST_END = 'on_quest_end';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'achievements';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'description'], 'required'],
            [['name'], 'unique'],
            [['image'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => ['jpg', 'jpeg', 'png', 'gif']],
            [['conditions'], 'string', 'max' => 500],
            [['type'], 'string', 'max' => 20],
            [['code'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'php', 'checkExtensionByMimeType' => false],
            [['image', 'conditions', 'code', 'type'], 'safe']
            //[['type'], 'in', 'range' => [Achievement::TYPE_UNIQUE, Achievement::TYPE_ON_ANSWER, Achievement::TYPE_ON_QUEST_END]]
            /*[['name', 'number', 'quest_id', 'question', 'answer'], 'required'],
            [['quest_id', 'number', 'top', 'left', 'case_depend'], 'integer'],
            [['number'], 'unique', 'targetAttribute' => ['quest_id', 'number']],
            [['name', 'description', 'success_message'], 'string', 'max' => 500],
            [['answer'], 'string'],
            [['description', 'case_depend', 'css', 'js', 'success_message', 'success_css', 'success_js'], 'safe'],
            [['css', 'success_css'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'css', 'checkExtensionByMimeType' => false],
            [['js', 'success_js'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'js', 'checkExtensionByMimeType' => false],
            */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [/*
            'id' => Yii::t('app', 'ID'),
            'name' => 'Заголовок',
            'number' => Yii::t('app', 'Number'),
            'quest_id' => Yii::t('app', 'Quest ID'),
			'question' => 'Вопрос',
			'answer' => 'Ответ',
            'description' => Yii::t('app', 'Description'),
			'case_depend' => 'регистрозависимый ответ',
			'time' => 'Время на ответ',*/
        ];
    }

    public function upload() {
        if($this->validate(['image'])) {
            $fn = md5($this->achievement_id.$this->name).'.'.$this->image->extension;

            if (!file_exists(ASSETS_DIR . DIRECTORY_SEPARATOR . 'achimage'))
                mkdir(ASSETS_DIR . DIRECTORY_SEPARATOR . 'achimage');

            $f = ASSETS_DIR . DIRECTORY_SEPARATOR . 'achimage' . DIRECTORY_SEPARATOR . $fn;

            if (file_exists($f))
                unlink($f);

            if ($this->image->saveAs($f)) {
                $this->image->name = $fn;

                return true;
            }
        }

        return false;
    }

    public function uploadCode() {
        if($this->validate(['code'])) {
            $fn = md5($this->achievement_id.$this->name).'.'.$this->code->extension;

            if (!file_exists(ASSETS_DIR . DIRECTORY_SEPARATOR . 'achicode'))
                mkdir(ASSETS_DIR . DIRECTORY_SEPARATOR . 'achicode');

            $f = ASSETS_DIR . DIRECTORY_SEPARATOR . 'achicode' . DIRECTORY_SEPARATOR . $fn;

            if (file_exists($f))
                unlink($f);

            if ($this->code->saveAs($f)) {
                $this->code->name = $fn;

                return true;
            }
        }

        return false;
    }

    public function getImagePath() {
        return ASSETS_DIR . DIRECTORY_SEPARATOR . 'achimage' . DIRECTORY_SEPARATOR . $this->image;
    }

    public function getCodePath() {
        return ASSETS_DIR . DIRECTORY_SEPARATOR . 'achicode' . DIRECTORY_SEPARATOR . $this->code;
    }

    public static function achievementsOnAnswer() {
        return Achievement::find()->where([
            'type' => Achievement::TYPE_ON_ANSWER
        ])->andWhere('
            achievement_id NOT IN (
                SELECT achievement_id
                FROM users_achievements
                WHERE user_id = '.Yii::$app->getUser()->getId().'
            )
        ')->all();
    }

    public static function achievementsOnQuestEnd() {
        return Achievement::find()->where([
            'type' => Achievement::TYPE_ON_QUEST_END
        ])->andWhere('
            achievement_id NOT IN (
                SELECT achievement_id
                FROM users_achievements
                WHERE user_id = '.Yii::$app->getUser()->getId().'
            )
        ')->all();
    }
    /*
    public static function eventOnAnswer() {
        $achivs = Achievement::find()->where(['type' => self::TYPE_ON_ANSWER])->all();

        foreach($achivs as $aciv) {
            try {
                include(eval(Html::decode($aciv->code)));
            } catch(Exception $e) {
                continue;
            }
        }
    }*/
}
