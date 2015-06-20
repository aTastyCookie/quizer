<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Quest */

$this->title = Yii::t('app', 'Create Quest');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quest-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
