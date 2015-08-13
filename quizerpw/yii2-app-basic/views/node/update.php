<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = Yii::t('app', 'Update Node').': '.$node->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['/node?quest_id=' . $node->quest_id]];
$this->params['breadcrumbs'][] = ['label' => $node->name, 'url' => ['view', 'id' => $node->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="node-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'node' => $node,
    ]) ?>

</div>
