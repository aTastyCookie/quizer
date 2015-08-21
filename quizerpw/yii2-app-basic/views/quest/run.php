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
    <h1><?= Html::encode($this->title) ?></h1>

    <?if($node && (count($node) == 1)):?>
        <h3>Вопрос: <?=$node->name?></h3>
        <br/>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($answer, 'text')->textarea(['maxlength' => true]) ?>
            <?= $form->field($answer, 'quest_id')->hiddenInput()->label(false) ?>
            <?= $form->field($answer, 'node_id')->hiddenInput()->label(false) ?>

           <?= Html::submitButton(Yii::t('app', 'Answer'), ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    <?elseif($node && (count($node) > 1)):?>
        <?foreach($node as $next):?>
            <a class="choose-next" href="<?=Url::toRoute(['quest/choose', 'quest_id' => $next->quest_id, 'node_id' => $next->id])?>">
                <div class="img"></div>
                <div class="description">
                    <?=$next->description?>
                </div>
            </a>
        <?endforeach?>
    <?else:?>
        <?if($quest->success_css):?>
            <link rel="stylesheet" href="/assets/qusuccfi/<?=$quest->success_css?>" />
        <?endif?>
        <?if($quest->success_js):?>
            <script src="/assets/qusuccfi/<?=$quest->success_js?>"></script>
        <?endif?>

        <?if($quest->success_message):?>
            <?=Html::decode($quest->success_message)?>
        <?else:?>
            <span>Поздравляем! Вы успешно прошли квест.</span>
        <?endif?>
    <?endif?>
</div>