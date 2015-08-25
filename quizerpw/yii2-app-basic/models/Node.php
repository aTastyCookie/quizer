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
    public $count_passed;
    public $count_in_proccess;
    public $avg_time;

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
            [['quest_id', 'number', 'top', 'left', 'case_depend'], 'integer'],
            [['number'], 'unique', 'targetAttribute' => ['quest_id', 'number']],
            [['name', 'description', 'success_message'], 'string', 'max' => 500],
            [['answer'], 'string'],
            [['description', 'case_depend', 'css', 'js', 'success_message', 'success_css', 'success_js'], 'safe'],
            [['css', 'success_css'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'css', 'checkExtensionByMimeType' => false],
            [['js', 'success_js'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'js', 'checkExtensionByMimeType' => false],
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
    public function getQuest() {
        return $this->hasOne(Quest::className(), ['id' => 'quest_id']);
    }

	public static function cleanConnections($quest_id) {
        foreach(NodeConnection::find()->where(['quest_id' => $quest_id])->all() as $connection)
            $connection->delete();

		 return true;
	}

    public function uploadCss() {
        if($this->validate(['css'])) {
            $fn = md5($this->quest_id.$this->name).'.'.$this->css->extension;

            if (!file_exists(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodextfi'))
                mkdir(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodextfi');

            $f = ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodextfi' . DIRECTORY_SEPARATOR . $fn;

            if (file_exists($f))
                unlink($f);

            if ($this->css->saveAs($f)) {
                $this->css->name = $fn;

                return true;
            }
        }

        return false;
    }

    public function uploadJs() {
        if($this->validate(['js'])) {
            $fn = md5($this->quest_id.$this->name).'.'.$this->js->extension;

            if (!file_exists(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodextfi'))
                mkdir(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodextfi');

            $f = ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodextfi' . DIRECTORY_SEPARATOR . $fn;

            if (file_exists($f))
                unlink($f);

            if ($this->js->saveAs($f)) {
                $this->js->name = $fn;

                return true;
            }
        }

        return false;
    }

    public function uploadSuccessCss() {
        if($this->validate(['success_css'])) {
            $fn = md5($this->quest_id.$this->name).'.'.$this->success_css->extension;

            if (!file_exists(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodsucfi'))
                mkdir(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodsucfi');

            $f = ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodsucfi' . DIRECTORY_SEPARATOR . $fn;

            if (file_exists($f))
                unlink($f);

            if ($this->success_css->saveAs($f)) {
                $this->success_css->name = $fn;

                return true;
            }
        }

        return false;
    }

    public function uploadSuccessJs() {
        if($this->validate(['success_js'])) {
            $fn = md5($this->quest_id.$this->name).'.'.$this->success_js->extension;

            if (!file_exists(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodsucfi'))
                mkdir(ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodsucfi');

            $f = ASSETS_DIR . DIRECTORY_SEPARATOR . 'nodsucfi' . DIRECTORY_SEPARATOR . $fn;

            if (file_exists($f))
                unlink($f);

            if ($this->success_js->saveAs($f)) {
                $this->success_js->name = $fn;

                return true;
            }
        }

        return false;
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
