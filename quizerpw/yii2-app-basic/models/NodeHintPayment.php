<?php

namespace app\models;

class NodeHintPayment extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'nodes_hints_payments';
    }

    public function rules() {
        return [

        ];
    }

    public function attributeLabels() {
        return [

        ];
    }
}