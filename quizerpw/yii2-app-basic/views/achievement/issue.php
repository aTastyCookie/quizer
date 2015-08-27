<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Issue Achievement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Achievements'), 'url' => ['achievement/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="achievement-issue">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="issue-achiv-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php echo $form->field($issue_achiv, 'user_id')->dropDownList(ArrayHelper::map($users, 'id', 'username'), ['prompt' => Yii::t('app', 'please, choose user')]) ?>
        <?php echo $form->field($issue_achiv, 'achievement_id')->dropDownList(ArrayHelper::map($achievements, 'achievement_id', 'name'), ['prompt' => Yii::t('app', 'please, choose achievement')]) ?>

        <div class="form-group">
            <?php echo Html::submitButton(Yii::t('app', 'Issue'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php if($message = Yii::$app->getSession()->getFlash('message')):?>
    <script>
        $(document).ready(function() {
            alert('<?php echo $message;?>');
        });
    </script>
<?php endif?>
