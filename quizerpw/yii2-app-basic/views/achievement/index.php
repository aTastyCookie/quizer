<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Achievements');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="achievements-index">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <p><?=Html::a(Yii::t('app', 'Create Achievement'), ['achievement/create'], ['class' => 'btn btn-success'])?></p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'achievement_id',
            'name',
            'description',
            'image',
            'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
