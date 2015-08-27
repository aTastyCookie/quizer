<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ARUser;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Achievements');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="achievements-index">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php if(ARUser::isAdmin()):?>
        <p>
            <?=Html::a(Yii::t('app', 'Create Achievement'), ['achievement/create'], ['class' => 'btn btn-success'])?>
            <?=Html::a(Yii::t('app', 'Issue Achievement'), ['achievement/issue'], ['class' => 'btn btn-success'])?>
        </p>
    <?endif?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name'
            ],
            [
                'attribute' => 'description',
            ],
            [
                'attribute' => 'conditions',
            ],
            [
                'attribute' => 'image',
                'visible' => ARUser::isAdmin()
            ],
            [
                'attribute' => 'code',
                'visible' => ARUser::isAdmin()
            ],
            [
                'attribute' => 'type',
                'visible' => ARUser::isAdmin()
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
