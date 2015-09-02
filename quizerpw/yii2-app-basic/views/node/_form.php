<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Node;

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

    <?php echo $form->field($node, 'question_type')->dropDownList(Node::$_transcript, ['prompt' => 'выберите из списка']) ?>

    <?php $is_textarea = (!$node->question_type || in_array($node->question_type, [
        Node::QUESTION_TYPE_TEXT,
        Node::QUESTION_TYPE_JS_CODE,
        Node::QUESTION_TYPE_PHP_CODE,
    ]));?>

    <?php $is_file = in_array($node->question_type, [
        Node::QUESTION_TYPE_IMAGE,
        Node::QUESTION_TYPE_JS_FILE,
        Node::QUESTION_TYPE_PHP_FILE,
    ]);?>

    <?php echo $form->field($node, 'question')->textarea([
        'id' => 'node-question-textarea',
        'name' => $is_textarea ? 'Node[question]' : 'hide',
        'style' => $is_textarea ? '' : 'display: none',
        'disabled' => !$is_textarea
    ])->label('Вопрос', [
        'for' => 'node-question-textarea',
        'style' => $is_textarea ? '' : 'display: none'
    ])->error([
        'style' => $is_textarea ? '' : 'display: none'
    ]) ?>
    <?php echo $form->field($node, 'question')->fileInput([
        'id' => 'node-question-file-field',
        'name' => $is_file ? 'Node[question]' : 'hide',
        'style' => $is_file ? '' : 'display: none',
        'disabled' => !$is_file
    ])->label('Вопрос', [
        'for' => 'node-question-file-field',
        'style' => $is_file ? '' : 'display: none'
    ])->error([
        'style' => $is_file ? '' : 'display: none'
    ]) ?>

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

<script>
    Array.prototype.in_array = function(p_val) {
        for(var i = 0, l = this.length; i < l; i++)	{
            if(this[i] == p_val) {
                return true;
            }
        }

        return false;
    }



    $(document).ready(function() {
        var textarea = [
            '<?php echo Node::QUESTION_TYPE_TEXT?>',
            '<?php echo Node::QUESTION_TYPE_JS_CODE?>',
            '<?php echo Node::QUESTION_TYPE_PHP_CODE?>'
        ];

        var fileField = [
            '<?php echo Node::QUESTION_TYPE_IMAGE?>',
            '<?php echo Node::QUESTION_TYPE_JS_FILE?>',
            '<?php echo Node::QUESTION_TYPE_PHP_FILE?>'
        ];

        <?php if($is_textarea):?>
            $('#node-question-file-field').prev('input').attr('name', 'hide');
        <?php endif?>

        $('#node-question_type').change(function() {
            if(textarea.in_array($(this).val())) {
                $('#node-question-file-field').hide();
                $('#node-question-file-field').attr('name', 'hide');
                $('#node-question-file-field').prop('disabled', true);
                $('#node-question-file-field').prev('input').attr('name', 'hide');
                $('#node-question-file-field').prev('input').prev('label').hide();
                $('#node-question-file-field').next('.help-block').hide();
                $('#node-question-textarea').show();
                $('#node-question-textarea').attr('name', 'Node[question]');
                $('#node-question-textarea').prop('disabled', false);
                $('#node-question-textarea').prev('label').show();
                $('#node-question-textarea').next('.help-block').show();
            } else if(fileField.in_array($(this).val())) {
                $('#node-question-textarea').hide();
                $('#node-question-textarea').attr('name', 'hide');
                $('#node-question-textarea').prop('disabled', true);
                $('#node-question-textarea').prev('label').hide();
                $('#node-question-textarea').next('.help-block').hide();
                $('#node-question-file-field').show();
                $('#node-question-file-field').attr('name', 'Node[question]');
                $('#node-question-file-field').prop('disabled', false);
                $('#node-question-file-field').prev('input').attr('name', 'Node[question]');
                $('#node-question-file-field').prev('input').prev('label').show();
                $('#node-question-file-field').next('.help-block').show();
            }
        });
    });
</script>