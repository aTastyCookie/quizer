<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\models\Node;
use app\models\NodeHint;
use app\models\Quest;


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
                    <?php if($hint->type == NodeHint::TYPE_FREE):?>
                        У вас есть подсказка. <a class="show-hint" href="#">Показать</a>?
                        <div class="hint-text" style="display: none;">
                            <?php echo $hint->message?>
                        </div>
                    <?php else:?>
                        Вы можете купить подсказку. <a class="show-hint" href="#">Купить</a>?
                        <div class="hint-text" style="display: none; padding-top: 20px;">
                            <p>Вы можете оплатить подсказку как с помощью карт Visa или Mastercard так и через сервис Яндекс Деньги.</p>
                            <p>Стоимость одной подсказки составляет: <?php echo Quest::HINT_COST?> руб.</p>
                            <p>Комиссия при оплате состовляет для оплаты через Яндекс Деньги - <?php echo Quest::HINT_COST_COMMISSION_YM * 100?>% a для оплаты через карты Visa и Mastercard - <?php echo Quest::HINT_COST_COMMISSION_CARD * 100?>%.</p>
                            <hr />
                            <div class="yandex-money">
                                <span style="display: inline-block; width: 260px; vertical-align: middle;">Оплата через Яндекс Деньги</span>
                                <iframe style="display: inline-block; vertical-align: middle;" frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/small.xml?account=410012326043712&quickpay=small&yamoney-payment-type=on&button-text=02&button-size=m&button-color=black&targets=%D0%9F%D0%BE%D0%BA%D1%83%D0%BF%D0%BA%D0%B0+%D0%BF%D0%BE%D0%B4%D1%81%D0%BA%D0%B0%D0%B7%D0%BA%D0%B8&default-sum=<?php echo Quest::HINT_COST + (Quest::HINT_COST * Quest::HINT_COST_COMMISSION_YM)?>&mail=on&successURL=<?php echo urlencode($_SERVER['REQUEST_URI'])?>" width="160" height="42"></iframe>
                            </div>
                            <hr />
                            <div class="visa-mastercard">
                                <span style="display: inline-block; width: 260px; vertical-align: middle;">Оплата с карты Visa или MasterCard</span>
                                <iframe style="display: inline-block; vertical-align: middle;" frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/small.xml?account=410012326043712&quickpay=small&any-card-payment-type=on&button-text=02&button-size=m&button-color=black&targets=%D0%9F%D0%BE%D0%BA%D1%83%D0%BF%D0%BA%D0%B0+%D0%BF%D0%BE%D0%B4%D1%81%D0%BA%D0%B0%D0%B7%D0%BA%D0%B8&default-sum=<?php echo Quest::HINT_COST + (Quest::HINT_COST * Quest::HINT_COST_COMMISSION_CARD)?>&mail=on&successURL=<?php echo urlencode($_SERVER['REQUEST_URI'])?>" width="160" height="42"></iframe>
                            </div>
                            <hr />
                        </div>
                    <?php endif?>
                </div><br /><br />
                <script>
                    $(document).ready(function() {
                        $('.show-hint').click(function() {
                            if($('.hint-text').hasClass('expanded')) {
                                $('.hint-text').slideToggle(function() {
                                    $('.hint-text').removeClass('expanded');
                                });
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo Url::to(['quest/requestpayment'])?>',
                                    data: {
                                        'hint_id': <?php echo $hint->hint_id?>
                                    },
                                    success: function (response) {
                                        $('.hint-text').slideToggle(function() {
                                            $('.hint-text').addClass('expanded');
                                        });
                                    },
                                    error: function () {
                                        alert('Ошибка сервера');
                                    }
                                });
                            }

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
        alert('Вы получили новое достижение: "<?php echo $achiv_name?>"!');
    </script>
<?php endif ?>