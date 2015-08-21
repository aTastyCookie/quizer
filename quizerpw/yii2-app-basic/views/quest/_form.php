<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Quest */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .quest-form .photo {
        position: relative;
        float: left;
        margin-right: 40px;
        width: 300px;
        height: 350px;
        background-image: url('/images/no-image.jpg');
        border: 1px solid #DFDFDF;
        background-size: contain;
        background-color: #FFFFFF;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 4px;
    }

    .quest-form .photo .quest-logo {
        margin-left: 15px;
    }

    .quest-form .photo .caption {
        position: absolute;
        width: 100%;
        height: 85px;
        bottom: 0;
        text-align: center;
        color: #ACACAC;
        font-size: 20px;
    }

    .quest-form #quest-logo {
        position: absolute;
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
        -webkit-opacity: 0;
        -khtml-opacity: 0;
        -moz-opacity: 0;
        -ms-opacity: 0;
        -o-opacity: 0;
        opacity: 0;
    }

    .quest-form #quest-complexity {
        display: none;
    }

    .quest-form .quest-info {
        float: left;
        max-width: 400px;
    }

    .quest-form .complexity {
        font-size: 0;
    }

    .quest-form .complexity .jackal {
        display: inline-block;
        width: 40px;
        height: 40px;
        background-image: url('/images/jackal-rating.png');
        background-position: -40px 0;
    }

    .quest-form .complexity .jackal.no {
        background-position: 0 0;
    }

    .form-horizontal .form-group {
        margin: 0;
    }

    .field-quest-date_start,
    .field-quest-date_finish {
        display: inline-block;
        max-width: 190px;
        vertical-align: top;
    }

    .hash-tag {
        display: inline-block;
        margin-bottom: 5px;
        width: 100px;
    }

    .hash-tags .add-tag {
        display: inline-block;
        width: 30px;
        height: 30px;
        vertical-align: middle;
        background-image: url('/images/add.png');
        background-repeat: no-repeat;
        cursor: pointer;
    }

    .hash-tags .add-tag:hover {
        background-position: -30px 0;
    }
</style>

<div class="quest-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
    ]); ?>
    <div class="photo" <?=$model->logo ? 'style="background-image: url(\''.$model->getLogoUrl().'\'); background-size: contain; background-color: #222222"' : ''?>>
        <div class="caption">
            <?if(!$model->logo):?>
                <?=Yii::t('app', 'Choose Image')?>
            <?endif?>
        </div>
        <?= $form->field($model, 'logo')->fileInput()->label(false) ?>
    </div>
    <div class="quest-info">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'complexity')->textInput() ?>
        <div class="complexity">
            <?for($i = 1; $i <= 10; $i++): ?>
                <a class="jackal <?=$model->complexity >= $i ? 'check' : 'no'?>" id="jackal-<?=$i?>" data-mark="<?=$i?>" href="#"></a>
            <?endfor?>
        </div>
        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'date_start')->widget(DatePicker::className(), [
            'options' => [
                'class' => 'form-control',
            ],
            'dateFormat' => 'dd.MM.y'
        ])->textInput() ?>&nbsp&nbsp&nbsp&nbsp
        <?= $form->field($model, 'date_finish')->widget(DatePicker::className(), [
            'options' => [
                'class' => 'form-control'
            ],
            'dateFormat' => 'dd.MM.y'
        ])->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    </div>
    <div style="clear: both"></div>

    <div class="hash-tags">
        <?=Html::label('Особые навыки', 'hash-tag-1')?>:<br />
        <?$i = 0;?>
        <?if(!empty($tags)):?>
            <?foreach($tags as $tag):?>
                <?=Html::textInput('HashTags['.$i.']', $tag->name, [
                    'class' => 'hash-tag show-tag form-control',
                    'id' => 'hash-tag-'.$i
                ])?>
                <?$i++?>
            <?endforeach?>
        <?endif?>
        <?=Html::textInput('HashTags['.$i.']', '', [
            'class' => 'hash-tag show-tag form-control',
            'id' => 'hash-tag-'.$i
        ])?>
        <?$i++?>
        <div class="add-tag"></div>
    </div>

    <?= $form->field($model, 'short')->textarea(['maxlength' => true]) ?>
    <?= $form->field($model, 'descr')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'success_message')->textInput() ?>
    <?= $form->field($model, 'success_css')->fileInput() ?>
    <?= $form->field($model, 'success_js')->fileInput() ?>

    <?= $form->field($model, 'is_closed')->checkbox() ?><br />

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
            'style' => 'margin-bottom: 5px;'
        ]) ?>
        <?if(!$model->isNewRecord):?>
            <?= Html::a(Yii::t('app', 'Update Tree'), Url::toRoute(['quest/visual', 'quest_id' => $model->id]), [
                'class' => 'btn btn-primary',
                'style' => 'margin-bottom: 5px;'
            ]) ?>
        <?endif?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    $(document).ready(function() {
        $('.complexity')
            .on('mouseover', '.jackal', function() {
                var mark = $(this).data('mark');

                $('.jackal.no').each(function() {
                    if($(this).data('mark') <= mark) {
                        $(this).removeClass('no');
                        $(this).addClass('yes');
                    }
                });

                $('.jackal.check').each(function() {
                    if($(this).data('mark') <= mark)
                        $(this).addClass('yes');
                    else
                        $(this).addClass('no');
                });
            })
            .on('mouseleave', '.jackal', function() {
                $('.jackal.yes').each(function() {
                    $(this).removeClass('yes');

                    if(!$(this).hasClass('check'))
                        $(this).addClass('no');
                });

                $('.jackal.no').each(function() {
                    if($(this).hasClass('check'))
                        $(this).removeClass('no');
                });
            })
            .on('click', '.jackal', function() {
                $('.jackal.check').each(function() {
                    $(this).removeClass('check');
                });

                $('.jackal.yes').each(function() {
                    $(this).removeClass('yes');
                    $(this).addClass('check');
                });

                $('#quest-complexity').val($(this).data('mark'));

                return false;
            });


        $('#quest-logo').change(function() {
            if($(this).val()) {
                $('.quest-form .photo').css({
                    'background-image': 'url(\'/images/loader.gif\')',
                    'background-size': 'auto',
                    'background-color': '#fff'
                });

                $('.quest-form .photo .caption').text('<?=Yii::t('app', 'Loading Image')?>');
                $.ajax({
                    type: 'POST',
                    url: '<?=Url::toRoute('quest/preload')?>',
                    data: new FormData($('.quest-form form').get(0)),
                    processData: false,
                    contentType: false,
                    success: function (json) {
                        response = JSON.parse(json);
                        console.log(response);

                        if (response['type'] == 'error') {
                            alert(response['message']);
                            $('.quest-form .photo').css({
                                'background-image': 'url(\'/images/no-image.jpg\')',
                                'background-size': 'contain'
                            });
                            $('.quest-form .photo .caption').text('<?=Yii::t('app', 'Choose Image')?>');
                        } else if (response['type'] == 'success') {
                            $('.quest-form .photo').css({
                                'background-image': 'url(\'' + response['resource'] + '\')',
                                'background-size': 'contain',
                                'background-color': '#222222'
                            });
                            $('.quest-form .photo .caption').text('');
                        }
                    },
                    error: function (err) {
                        alert('Ошибка сервера');
                        console.log(err);
                    }
                });
            }
        });

        var tag_id = <?=$i?>;

        $('.add-tag').click(function() {
            $(this).before(
                '<input type="text" id="hash-tag-' + tag_id + '" class="hash-tag show-tag form-control" name="HashTags[' + tag_id + ']" value="" /> '
            )
            tag_id++;
        });
    });
</script>