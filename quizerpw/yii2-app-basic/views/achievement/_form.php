<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Achievement;

/* @var $this yii\web\View */
/* @var $model app\models\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="achiv-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?php echo $form->field($achiv, 'name')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($achiv, 'description')->textarea(['maxlength' => true]) ?>
    <?php echo $form->field($achiv, 'image')->fileInput() ?>
    <?php echo $form->field($achiv, 'type')->dropDownList([
        Achievement::TYPE_UNIQUE => 'уникальная',
        Achievement::TYPE_ON_ANSWER => 'после ответа',
        Achievement::TYPE_ON_QUEST_END => 'после завершения квеста'
    ]) ?>
    <?php echo $form->field($achiv, 'conditions')->textarea() ?>

    <div class="form-group">
        <?php echo Html::submitButton($achiv->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $achiv->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>