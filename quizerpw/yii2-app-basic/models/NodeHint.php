<?php

namespace app\models;

class NodeHint extends \yii\db\ActiveRecord {

    const TYPE_FREE = 'free';
    const TYPE_PAY = 'pay';

    public static $_transcript = [
        self::TYPE_FREE => 'бесплатная',
        self::TYPE_PAY => 'платная'
    ];

    public static function tableName() {
        return 'nodes_hints';
    }

    public function rules() {
        return [
            [['attemp', 'type', 'message'], 'required'],
            [['attemp'], 'integer'],
            [['type'], 'in', 'range' => [self::TYPE_FREE, self::TYPE_PAY]],
            [['message'], 'string', 'max' => 500]
        ];
    }

    public function attributeLabels() {
        return [
            /*
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Answer'),*/
        ];
    }
}