<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = Yii::t('app', 'Create Node');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['quest/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index', 'quest_id' => Yii::$app->getRequest()->get('quest_id')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'node' => $node,
    ]) ?>

</div>
