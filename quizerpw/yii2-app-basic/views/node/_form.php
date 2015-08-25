<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?php echo $form->field($node, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($node, 'quest_id')->hiddenInput()->label(false) ?>
    <?php echo $form->field($node, 'number')->textInput() ?>
    <?php echo $form->field($node, 'question')->textInput() ?>
    <?php echo $form->field($node, 'answer')->textInput() ?>
    <?php echo $form->field($node, 'description')->textarea() ?>
    <?php echo $form->field($node, 'case_depend')->checkbox() ?>
    <?php echo $form->field($node, 'css')->fileInput() ?>
    <?php echo $form->field($node, 'js')->fileInput() ?>

    <?php echo $form->field($node, 'success_message')->textInput() ?>
    <?php echo $form->field($node, 'success_css')->fileInput() ?>
    <?php echo $form->field($node, 'success_js')->fileInput() ?>

    <?php if(!Yii::$app->getRequest()->getIsAjax()):?>
        <div class="form-group">
            <?php echo Html::submitButton($node->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $node->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php endif?>

    <?php ActiveForm::end(); ?>

</div>