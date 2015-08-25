<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quest Answers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['quest/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quest Statistics').': '.$run->quest->name, 'url' => ['quest/statistics', 'id' => $run->quest->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quest Statistics by User').': '.$run->user->username, 'url' => ['quest/userstatistics', 'quest_id' => $run->quest->id, 'user_id' => $run->user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .correct {
        background-color: #5CB85C !important;

    }

    .wrong {
        background-color: #D9534F !important;

    }
</style>
<div class="run-answers">
	<h1><?php echo Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
        'rowOptions' => function ($data) {
            return [
                'class' => $data->status ? 'correct' : 'wrong'
            ];
        },
		'columns' => [
			[
                'header' => '#',
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;'],
            ],
            [
                'attribute' => 'node.question',
                'value' => function($data) {return $data->node->question;},
                'headerOptions' => ['style' => 'text-align: center;']
            ],
            [
                'attribute' => 'text',
                'headerOptions' => ['style' => 'text-align: center;']
            ],

            /*
            [
                'attribute' => Yii::t('app', 'Right / Wrong'),
                'format' => 'raw',
                'value' => function($data) {
                    $statistics = $data->getAnswersStatistics();

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
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['quest/runanswers', 'run_id' => $data->run_id], [
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
           /*     ],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],*/
		],
	]); ?>

</div>
