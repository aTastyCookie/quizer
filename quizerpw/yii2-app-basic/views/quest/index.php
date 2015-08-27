<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ARUser;
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

    .highscores {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/highscores.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .highscores:hover {
        background-position: -20px 0;
    }

    .user-question-conformity {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/user-question-conformity.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .user-question-conformity:hover {
        background-position: -20px 0;
    }

    .statistics {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
        background-image: url('/images/statistics.png');
        background-repeat: no-repeat;
        background-size: 40px auto;
        cursor: pointer;
        outline: 0;
    }

    .statistics:hover {
        background-position: -20px 0;
    }

    .btn-disabled {
        color: #fff;
        background-color: #aaa;
        border-color: #999;
        cursor: default;
    }

    .btn-disabled:hover,
    .btn-disabled:active {
        color: #fff;
    }
</style>

<div class="quest-index">
	<h1><?php echo Html::encode($this->title) ?></h1>
	<?php if(ARUser::isAdmin()):?>
	    <p><?php echo Html::a(Yii::t('app', 'Create Quest'), ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php endif?>

	<?php echo GridView::widget([
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
                'visible' => ARUser::isAdmin()
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
                'visible' => ARUser::isAdmin()
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
                'attribute' => Yii::t('app', 'Доступность'),
                'format' => 'raw',
                'value' => function($data) {return $data->getDatesPeriod();},
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
            [
                'attribute' => '',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a(Yii::t('app', 'Quest Highscores'), Url::toRoute(['quest/highscores', 'quest_id' => $data->id]), [
                        'class' => 'btn btn-danger',
                    ]);
                },
                'contentOptions' =>  ['style' => 'text-align: center;'],
                'visible' => !ARUser::isAdmin()
            ],
            [
                'attribute' => '',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a(Yii::t('app', 'Quest Tree'), Url::toRoute(['quest/visual', 'quest_id' => $data->id]), [
                        'class' => 'btn btn-success',
                    ]);
                },
                'contentOptions' =>  ['style' => 'text-align: center;'],
                'visible' => !ARUser::isAdmin()
            ],
            [
                'attribute' => '',
                'format' => 'raw',
                'value' => function($data) {
                    $params = $data->url ? ['quest/run', 'url' => $data->url] : ['quest/run', 'id' => $data->id];
                    $date_begin = new \DateTime($data->date_start);
                    $date_end = new \DateTime($data->date_finish);
                    $date_cur = new \DateTime(date('Y-m-d H:i:s'));
                    $is_disabled = !(($date_cur >= $date_begin) && ($date_cur <= $date_end));

                    return Html::a('Пройти', $is_disabled ? ' ' : Url::toRoute($params), [
                        'class' => $is_disabled ? 'btn btn-disabled' : 'btn btn-success',
                        'onclick' => $is_disabled ? 'return false;' : ''
                    ]);
                },
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
			[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}<br />{create_node} {view_nodes} {edit_tree}<br />{highscores} {user_question_conformity} {statistics}',
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
                    'highscores' => function ($url, $data, $key) {
                        return Html::a('', Url::toRoute(['quest/highscores', 'quest_id' => $data->id]), [
                            'class' => 'highscores',
                            'title' => Yii::t('app', 'Quest Highscores')
                        ]);
                    },
                    'user_question_conformity' => function ($url, $data, $key) {
                        return Html::a('', Url::toRoute(['quest/conformity', 'quest_id' => $data->id]), [
                            'class' => 'user-question-conformity',
                            'title' => Yii::t('app', 'Quest User-Quest Conformity')
                        ]);
                    },
                    'statistics' => function ($url, $data, $key) {
                        return Html::a('', Url::toRoute(['quest/statistics', 'id' => $data->id]), [
                            'class' => 'statistics',
                            'title' => Yii::t('app', 'Quest Statistics')
                        ]);
                    },
                ],
                'contentOptions' =>  ['style' => 'text-align: center;'],
                'visible' => ARUser::isAdmin()
            ],
		],
	]); ?>

</div>
