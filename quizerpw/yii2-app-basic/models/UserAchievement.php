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
class UserAchievement extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'users_achievements';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'achievement_id'], 'required'],
            [['user_id', 'achievement_id'], 'integer'],
            [['achievement_id'], 'unique', 'targetAttribute' => ['user_id', 'achievement_id'], 'message' => 'У этого пользователя уже есть данное достижение'],
            [['user_id'], 'exist', 'targetClass' => 'app\models\ARUser', 'targetAttribute' => 'id'],
            [['achievement_id'], 'exist', 'targetClass' => 'app\models\Achievement', 'targetAttribute' => 'achievement_id'],
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

    public function beforeSave($insert) {
        if(empty($this->datetime))
            $this->datetime = date('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public static function assignAchievment($achiv) {
        $user_achiv = new UserAchievement();
        $user_achiv->user_id = Yii::$app->getUser()->getId();
        $user_achiv->achievement_id = $achiv->achievement_id;
        $user_achiv->datetime = date('Y-m-d H:i:s');
        $user_achiv->save(false);

        Yii::$app->getSession()->setFlash('achievement', $achiv->name);
    }

    public function getAchievement() {
        return $this->hasOne(Achievement::className(), ['achievement_id' => 'achievement_id']);
    }
}
