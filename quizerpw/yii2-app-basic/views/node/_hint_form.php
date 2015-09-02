<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\NodeHint;

/* @var $this yii\web\View */
/* @var $model app\models\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-hint-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($hint, 'attemp')->textInput() ?>

    <?php echo $form->field($hint, 'type')->dropDownList(NodeHint::$_transcript)?>
    <?php echo $form->field($hint, 'message')->textarea() ?>

    <div class="form-group">
        <?php echo Html::submitButton($node->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $node->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>