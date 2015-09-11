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

    <?php if(Yii::$app->user->identity->getIsAdmin()):?>
        <p>
            <?=Html::a(Yii::t('app', 'Create Achievement'), ['achievement/create'], ['class' => 'btn btn-success'])?>
            <?=Html::a(Yii::t('app', 'Issue Achievement'), ['achievement/issue'], ['class' => 'btn btn-success'])?>
        </p>
    <?php endif?>

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
                'visible' => Yii::$app->user->identity->getIsAdmin()
            ],
            [
                'attribute' => 'code',
                'visible' => Yii::$app->user->identity->getIsAdmin()
            ],
            [
                'attribute' => 'type',
                'visible' => Yii::$app->user->identity->getIsAdmin()
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
