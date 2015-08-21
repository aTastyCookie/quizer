<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quests');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .quest-index .jackal {
        display: inline-block;
        width: 20px;
        height: 20px;
        background-image: url('/images/jackal-rating.png');
        background-size: 40px auto;
        background-position: -20px 0;
    }

    .quest-index .jackal.no {
        background-position: 0 0;
    }

    .view-quest {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/view.png');
        background-size: 40px auto;
        background-repeat: no-repeat;
        cursor: pointer;
        outline: 0;
    }

    .view-quest:hover {
        background-position: -20px 0;
    }

    .update-quest {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/edit.png');
        background-size: 40px auto;
        background-repeat: no-repeat;
        cursor: pointer;
        outline: 0;
    }

    .update-quest:hover {
        background-position: -20px 0;
    }

    .delete-quest {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/delete.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .delete-quest:hover {
        background-position: -20px 0;
    }

    .create-node {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/add-node.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .create-node:hover {
        background-position: -20px 0;
    }

    .view-nodes {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/view-node.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .view-nodes:hover {
        background-position: -20px 0;
    }

    .edit-tree {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/edit-tree.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .edit-tree:hover {
        background-position: -20px 0;
    }
</style>

<div class="quest-index">
	<h1><?= Html::encode($this->title) ?></h1>
	<?if(User::isAdmin()):?>
	    <p><?= Html::a(Yii::t('app', 'Create Quest'), ['create'], ['class' => 'btn btn-success']) ?></p>
    <?endif?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;'],
            ],
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;'],
                'visible' => User::isAdmin()
            ],
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'complexity',
                'format' => 'raw',
                'value' => function($data) {
                    $html = '';

                    for($i = 1; $i <= 10; $i++) {
                        $html .= '<div class="jackal '.($data->complexity >= $i ? 'check' : 'no').'"></div>';
                    }

                    return $html;
                },
                'headerOptions' => ['style' => 'text-align: center; width: 230px;'],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
            [
                'attribute' => 'url',
                'format' => 'url',
                'headerOptions' => ['style' => 'text-align: center;'],
                'visible' => User::isAdmin()
            ],
			// 'short',
			// 'descr:ntext',
			// 'date_start',
			// 'date_finish',
			// 'password',

			[
				'attribute' => Yii::t('app', 'Nodes'),
				'format' => 'raw',
				'value' => function($data) {return $data->getNodeCount().' шт.';},
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;']
			],
            [
                'attribute' => '',
                'format' => 'raw',
                'value' => function($data) {return Html::a('Пройти', Url::toRoute(['quest/run', 'id' => $data->id]), [
                    'class' => 'btn btn-success'
                ]);},
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
			[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}<br />{create_node} {view_nodes} {edit_tree}',
                'buttons' => [
                    'view' => function ($url, $data, $key) {
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
                    },
                ],
                'contentOptions' =>  ['style' => 'text-align: center;'],
                'visible' => User::isAdmin()
            ],
		],
	]); ?>

</div>
