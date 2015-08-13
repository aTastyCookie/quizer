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
            [['date_finish'], 'safe'],
            [['name', 'logo'], 'string', 'max' => 500],
            [['short'], 'string', 'max' => 200],
            [['descr'], 'string', 'max' => 1500],
            [['url', 'password'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }

    public static function getFirstNode( $questId ) {
        $current = Node::find()
            ->where(['quest_id' => $questId])
            ->andFilterWhere(['prev' => 0])
            ->andWhere(['>', 'next', '0'])
            ->one();
        return $current;
    }

    public static function getChain( $questId )
    {
        $chain = array();
        $current = self::getFirstNode( $questId );

        if (!$current) {
            return $chain;
        }
        //$chain[] = $current;

        while (true) {
            if ($current->next) {
                $next = Node::findOne( $current->next );
                if ($next) {
                    $chain[] = array('src' => $current->id, 'trg' => $next->id, 'type' => 'next');
                    if ($current->prev2) {
                        $chain[] = array('src' => $current->id, 'trg' => $current->prev2, 'type' => 'prev');
                    }
                    $current = $next;

                }else {
                    //break;
                }
            }elseif ($current->prev2) {
                $chain[] = array('src' => $current->id, 'trg' => $current->prev2, 'type' => 'prev');
                break;
            }else {
                break;
            }
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
