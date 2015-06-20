<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Quest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quest-form">

     <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                    ]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

   <?php 
	if ($model->logo) {?>
		<div style="text-align: center;">
			<img src="<?=$model->getLogoUrl()?>">
		</div>
	<?php } ?>

	<?= $form->field($model, 'logo')->fileInput() ?>

    <?= $form->field($model, 'complexity')->textInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descr')->textarea(['rows' => 6]) ?>

   
	<?= $form->field($model, 'date_start')->widget(DatePicker::className(),['clientOptions' => ['defaultDate' => $model->date_start]]) ?>
	<?= $form->field($model, 'date_finish')->widget(DatePicker::className(),['clientOptions' => ['defaultDate' => $model->date_finish]]) ?>

	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
