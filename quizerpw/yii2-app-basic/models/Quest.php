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
class Quest extends \yii\db\ActiveRecord {

    public $logoFile;
    public $cssFile;
    public $jsFile;

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
            [['date_start', 'date_finish'], 'date', 'format' => 'dd.MM.y'],
            [['date_finish', 'is_closed', 'success_message', 'success_css', 'success_js'], 'safe'],
            [['name', 'logo', 'success_message'], 'string', 'max' => 500],
            [['short'], 'string', 'max' => 200],
            [['descr'], 'string', 'max' => 1500],
            [['url', 'password'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['success_css'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'css'],
            [['success_js'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'extensions' => 'js'],
        ];
    }

    public static function getParentNodes($quest_id, $is_one = false) {
        $parents = Node::find()
            ->where(['quest_id' => $quest_id])
            ->andFilterWhere(['prev' => 0])
            ->andWhere(['>', 'next', '0']);

        if($is_one)
            return $parents->one();
        else
            return $parents->all();
    }

    public static function getChain($quest_id) {
        $chain = [];

        foreach(NodeConnection::find()->where(['quest_id' => $quest_id])->all() as $connection) {
            $chain[] = [
                'src' => $connection->from_node_id,
                'trg' => $connection->to_node_id,
                'type' => $connection->type
            ];
        }

        return $chain;
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
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'logo' => Yii::t('app', 'Logo'),
            'complexity' => Yii::t('app', 'Complexity'),
            'url' => Yii::t('app', 'Url'),
            'short' => Yii::t('app', 'Short Description'),
            'descr' => Yii::t('app', 'Full Description'),
            'date_start' => Yii::t('app', 'Date Start'),
            'date_finish' => Yii::t('app', 'Date Finish'),
            'password' => Yii::t('app', 'Password'),
            'success_css' => Yii::t('app', 'CSS-file on complete quest'),
            'success_js' => Yii::t('app', 'JS-file on complete quest'),
            'is_closed' => Yii::t('app', 'Closed by Admin')
        ];
    }

    public function upload($upload_dir = null, $file_name = null, $no_validate = false) {
        if ($this->validate($no_validate ? [] : null)) {
            $rand = $file_name ? : \Yii::$app->security->generateRandomString();
            $fn = $rand . '.' . $this->logoFile->extension;
            $f = ($upload_dir ? : UPLOAD_DIR) . '/' . $fn;

            if(file_exists($f))
                unlink($f);

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

    public function uploadCss() {
        if ($this->validate()) {
            $fn = md5($this->id).'.'.$this->cssFile->extension;

            if(!file_exists(ASSETS_DIR.DIRECTORY_SEPARATOR.'qusuccfi'))
                mkdir(ASSETS_DIR.DIRECTORY_SEPARATOR.'qusuccfi');

            $f = ASSETS_DIR.DIRECTORY_SEPARATOR.'qusuccfi'.DIRECTORY_SEPARATOR.$fn;

            if(file_exists($f))
                unlink($f);

            if ($this->cssFile->saveAs($f)) {
                $this->cssFile->name = $fn;

                return true;
            }
        }
        return false;
    }

    public function uploadJs() {
        if ($this->validate()) {
            $fn = md5($this->id).'.'.$this->jsFile->extension;

            if(!file_exists(ASSETS_DIR.DIRECTORY_SEPARATOR.'qusuccfi'))
                mkdir(ASSETS_DIR.DIRECTORY_SEPARATOR.'qusuccfi');

            $f = ASSETS_DIR.DIRECTORY_SEPARATOR.'qusuccfi'.DIRECTORY_SEPARATOR.$fn;

            if(file_exists($f))
                unlink($f);

            if ($this->jsFile->saveAs($f)) {
                $this->jsFile->name = $fn;

                return true;
            }
        }
        return false;
    }

    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getLogoFile() {
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
