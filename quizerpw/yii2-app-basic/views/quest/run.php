<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Quest */

$this->title = Yii::t('app', 'Run Quest').': '.$quest->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .choose-next {
        display: inline-block;
        margin-right: 20px;
        border: 1px solid #DFDFDF;
        border-radius: 4px;
    }

    .choose-next .img {
        width: 220px;
        height: 300px;
        background-image: url('/images/question.png');
        background-size: 160px auto;
        background-position: center;
        background-repeat: no-repeat;
    }

    .choose-next .description {
        padding: 10px;
    }
</style>

<div class="quest-run">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php if($node && (count($node) == 1) && !empty($answer)):?>
        <?php if($node->css):?>
    <link rel="stylesheet" href="/assets/nodextfi/<?php echo $node->css?>" />
    <?php endif?>
    <?php if($node->js):?>
        <script src="/assets/nodextfi/<?php echo $node->js?>"></script>
    <?php endif?>

        <h3>Вопрос: <?php echo $node->name?></h3>
    <br/>

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($answer, 'text')->textarea(['maxlength' => true]) ?>
    <?php echo $form->field($answer, 'quest_id')->hiddenInput()->label(false) ?>
    <?php echo $form->field($answer, 'node_id')->hiddenInput()->label(false) ?>

    <?php echo Html::submitButton(Yii::t('app', 'To Answer'), ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    <?php elseif($node && (count($node) > 1) && !empty($answer)):?>
    <?php foreach($node as $next):?>
        <a class="choose-next" href="<?php echo Url::toRoute(['quest/choose', 'quest_id' => $next->quest_id, 'node_id' => $next->id])?>">
            <div class="img"></div>
            <div class="description">
                <?php echo $next->description?>
            </div>
        </a>
    <?php endforeach?>
    <?php else:?>
    <?php if($node->success_css):?>
        <link rel="stylesheet" href="/assets/nodsucfi/<?php echo $node->success_css?>" />
    <?php elseif($quest->success_css):?>
        <link rel="stylesheet" href="/assets/qusuccfi/<?php echo $quest->success_css?>" />
    <?php endif?>

    <?php if($node->success_js):?>
        <script src="/assets/nodsucfi/<?php echo $node->success_js?>"></script>
    <?php elseif($quest->success_js):?>
        <script src="/assets/qusuccfi/<?php echo $quest->success_js?>"></script>
    <?php endif?>

    <?php if($node->success_message):?>
        <?php echo Html::decode($node->success_message)?>
    <?php elseif($quest->success_message):?>
        <?php echo Html::decode($quest->success_message)?>
    <?php else:?>
        <span>Поздравляем! Вы успешно прошли квест.</span>
    <?php endif?>
    <?php endif?>
</div>