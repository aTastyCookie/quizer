<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ARUser;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Received Achievements');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="achievements-index">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'achievement.name'
            ],
            [
                'attribute' => 'achievement.description',
            ],
            [
                'attribute' => 'achievement.conditions',
            ]
        ],
    ]); ?>

</div>
