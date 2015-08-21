<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quest Statistics').': '.$quest->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['quest/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .has-success {
        color: #3c763d;
    }

    .has-error {
        color: #a94442;
    }
</style>
<div class="quest-statistics">
	<h1><?= Html::encode($this->title) ?></h1>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;'],
            ],
            [
                'attribute' => 'username',
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => Yii::t('app', 'Complete / Not complete'),
                'format' => 'raw',
                'value' => function($data) {
                    $statistics = $data->getCompleteStatisticsByQuest(Yii::$app->getRequest()->get('id'));

                    return '<span class="has-success">'.$statistics['complete'].'</span> / <span class="has-error">'.$statistics['no'].'</span>';
                },
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;']
            ],
            [
                'attribute' => Yii::t('app', 'Right / Wrong'),
                'format' => 'raw',
                'value' => function($data) {
                    $statistics = $data->getAnswersStatisticsByQuest(Yii::$app->getRequest()->get('id'));

                    return '<span class="has-success">'.$statistics['right'].'</span> / <span class="has-error">'.$statistics['wrong'].'</span>';
                },
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;']
            ],
			[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $data, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['quest/userstatistics', 'quest_id' => Yii::$app->getRequest()->get('id'), 'user_id' => $data->id], [
                            'class' => 'view-quest',
                            'title' => Yii::t('app', 'View')
                        ]);
                    }
                    /*'view' => function ($url, $data, $key) {
                        return Html::a('', $url, [
                            'class' => 'view-quest',
                            'title' => Yii::t('app', 'View Quest')
                        ]);
                    },
                    'update' => function ($url, $data, $key) {
                        return Html::a('', $url, [
                            'class' => 'update-quest',
                            'title' => Yii::t('app', 'Update Quest')
                        ]);
                    },
                    'delete' => function ($url, $data, $key) {
                        return Html::a('', $url, [
                            'class' => 'delete-quest',
                            'title' => Yii::t('app', 'Delete Quest'),
                            'onclick' => 'return confirm(\''.Yii::t('app', 'Are you sure?').'\') ? true : false;'
                        ]);
                    },
                    'create_node' => function ($url, $data, $key) {
                        return Html::a('', Url::toRoute(['node/create', 'quest_id' => $data->id]), [
                            'class' => 'create-node',
                            'title' => Yii::t('app', 'Create Node')
                        ]);
                    },
                    'view_nodes' => function ($url, $data, $key) {
                        return Html::a('', Url::toRoute(['node/index', 'quest_id' => $data->id]), [
                            'class' => 'view-nodes',
                            'title' => Yii::t('app', 'View Nodes')
                        ]);
                    },
                    'edit_tree' => function ($url, $data, $key) {
                        return Html::a('', Url::toRoute(['quest/visual', 'quest_id' => $data->id]), [
                            'class' => 'edit-tree',
                            'title' => Yii::t('app', 'Update Quest Tree')
                        ]);
                    },*/
                ],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
		],
	]); ?>

</div>
