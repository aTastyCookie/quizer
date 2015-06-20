<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quest".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property integer $complexity
 * @property string $url
 * @property string $short
 * @property string $descr
 * @property string $date_start
 * @property string $date_finish
 * @property string $password
 */
class Quest extends \yii\db\ActiveRecord
{
	public $logoFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date_start'], 'required'],
            [['complexity'], 'integer'],
            [['descr'], 'string'],
            [['date_start', 'date_finish'], 'safe'],
            [['name', 'logo', 'short'], 'string', 'max' => 500],
            [['url', 'password'], 'string', 'max' => 255]
        ];
    }

	/** 
		return count of child nodes
	*/
	
	public function getNodeCount()
	{
		return Node::find()
			->where(['quest_id' => $this->id])
			->count();
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'logo' => Yii::t('app', 'Logo'),
            'complexity' => Yii::t('app', 'Complexity'),
            'url' => Yii::t('app', 'Url'),
            'short' => Yii::t('app', 'Short'),
            'descr' => Yii::t('app', 'Descr'),
            'date_start' => Yii::t('app', 'Date Start'),
            'date_finish' => Yii::t('app', 'Date Finish'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

	public function upload()
     {
        if ($this->validate()) {
			$rand = \Yii::$app->security->generateRandomString();
			$fn = $rand . '.' . $this->logoFile->extension;
			$f = UPLOAD_DIR . '/' . $fn;

            if ($this->logoFile->saveAs($f)) {
				
				$file = \Yii::getAlias($f); 
				$image = \Yii::$app->image->load($file);
				$image->resize(400, 400);
				$image->save($f);

				$this->logoFile->name = $fn;
				 return true;
			}
        }
        return false;
    }

	 /**
     * fetch stored image file name with complete path 
     * @return string
     */
		public function getLogoFile() 
		{
			 $avatar = isset($this->logo) ? $this->logo : false;
			 return UPLOAD_DIR . '/' . $this->logo;
		} 

		/**
		 * fetch stored image url
		 * @return string
		 */
		 public function getLogoUrl() 
		 {
			$avatar = isset($this->logo) ? $this->logo : false;
			return '/'. UPLOAD_DIR . '/' . $this->logo;
		}

}
