<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($node, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($node, 'quest_id')->hiddenInput()->label(false) ?>
    <?= $form->field($node, 'number')->textInput() ?>
    <?= $form->field($node, 'question')->textInput() ?>
    <?= $form->field($node, 'answer')->textInput() ?>
    <?= $form->field($node, 'description')->textarea() ?>
    <?= $form->field($node, 'case_depend')->checkbox() ?>

    <?if(!Yii::$app->getRequest()->getIsAjax()):?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $node->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?endif?>

    <?php ActiveForm::end(); ?>

</div>