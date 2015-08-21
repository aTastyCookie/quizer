<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => '/quest'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['/node?quest_id=' . $model->quest_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Node').': '.$this->title;
?>
<div class="node-view">

    <h1><?=Yii::t('app', 'Node')?>: <?=$this->title?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'quest_id',
        ],
    ]) ?>

</div>
