<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\models\Node;
use app\models\NodeHint;


/* @var $this yii\web\View */
/* @var $model app\models\Quest */

$this->title = Yii::t('app', 'Run Quest') . ': ' . $quest->name;
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

        <?php if ($node && (count($node) == 1) && !empty($answer)): ?>
            <?php if ($node->css): ?>
        <link rel="stylesheet" href="/assets/nodextfi/<?php echo $node->css ?>"/>
        <?php endif ?>
        <?php if ($node->js): ?>
            <script src="/assets/nodextfi/<?php echo $node->js ?>"></script>
        <?php endif ?>

            <h3>Вопрос: <?php echo $node->name ?></h3>
        <br/>

            <?php if($hint):?>
                <div class="hint">
                    У вас есть подсказка. <a class="show-hint" href="#">Показать</a>?
                    <div class="hint-text" style="display: none;">
                        <?php if($hint->type == NodeHint::TYPE_FREE):?>
                            <?php echo $hint->message?>
                        <?php else:?>
                            Тут будет форма оплаты
                        <?php endif?>
                    </div>
                </div><br /><br />
                <script>
                    $(document).ready(function() {
                        $('.show-hint').click(function() {
                            $('.hint-text').slideToggle();

                            return false;
                        });
                    });
                </script>
            <?php endif?>

            <div class="question">
                <?php
                    switch($node->question_type) {
                        case Node::QUESTION_TYPE_TEXT:
                            echo Html::decode($node->question);
                            break;
                        case Node::QUESTION_TYPE_IMAGE:
                            echo Html::img($node->getQuestionFileUrl(), ['style' => 'max-height: 300px;']);
                            break;
                        case Node::QUESTION_TYPE_JS_CODE:
                            echo '<script>'.Html::decode($node->question).'</script>';
                            break;
                        case Node::QUESTION_TYPE_JS_FILE:
                            echo '<script src="'.$node->getQuestionFileUrl().'"></script>';
                            break;
                        case Node::QUESTION_TYPE_PHP_CODE:
                            eval(Html::decode($node->question));
                            break;
                        case Node::QUESTION_TYPE_PHP_FILE:
                            include($node->getQuestionFilePath());
                            break;
                    }
                ?>
            </div><br />

            <?php $form = ActiveForm::begin(); ?>

                <?php echo $form->field($answer, 'text')->textarea(['maxlength' => true]) ?>
                <?php echo $form->field($answer, 'quest_id')->hiddenInput()->label(false) ?>
                <?php echo $form->field($answer, 'node_id')->hiddenInput()->label(false) ?>
                <?php if ($answer->scenario == 'captcha'): ?>
                    <?php echo $form->field($answer, 'captcha')->widget(Captcha::className()) ?>
                <?php endif ?>

                <?php echo Html::submitButton(Yii::t('app', 'To Answer'), ['class' => 'btn btn-success']) ?>

            <?php ActiveForm::end(); ?>
        <?php elseif ($node && (count($node) > 1) && !empty($answer)): ?>
            <?php foreach ($node as $next): ?>
            <a class="choose-next"
               href="<?php echo Url::toRoute(['quest/choose', 'quest_id' => $next->quest_id, 'node_id' => $next->id]) ?>">
                <div class="img"></div>
                <div class="description">
                    <?php echo $next->description ?>
                </div>
            </a>
        <?php endforeach ?>
        <?php else: ?>
        <?php if ($node->success_css): ?>
        <link rel="stylesheet" href="/assets/nodsucfi/<?php echo $node->success_css ?>"/>
        <?php elseif ($quest->success_css): ?>
        <link rel="stylesheet" href="/assets/qusuccfi/<?php echo $quest->success_css ?>"/>
        <?php endif ?>

        <?php if ($node->success_js): ?>
            <script src="/assets/nodsucfi/<?php echo $node->success_js ?>"></script>
        <?php elseif ($quest->success_js): ?>
            <script src="/assets/qusuccfi/<?php echo $quest->success_js ?>"></script>
        <?php endif ?>

        <?php if ($node->success_message): ?>
            <?php echo Html::decode($node->success_message) ?>
        <?php elseif ($quest->success_message): ?>
            <?php echo Html::decode($quest->success_message) ?>
        <?php else: ?>
            <span>Поздравляем! Вы успешно прошли квест.</span>
        <?php endif ?>
        <?php endif ?>
    </div>

<?php if ($achiv_name = Yii::$app->getSession()->getFlash('achievement')): ?>
    <script>
        alert('Вы получили новое достижение: "<?echo $achiv_name?>"!');
    </script>
<?php endif ?>