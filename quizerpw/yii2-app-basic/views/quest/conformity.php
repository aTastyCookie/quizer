<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quest User-Quest Conformity').': '.$quest->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['quest/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="quest-conformity">
	<h1><?php echo Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
            [
                'attribute' => 'number',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;'],
            ],
            [
                'attribute' => Yii::t('app', 'Node Count Passed'),
                'value' => function($data) {return $data->count_passed;},
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => Yii::t('app', 'Node Count In Process'),
                'value' => function($data) {return $data->count_in_proccess;},
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => Yii::t('app', 'Node Avg Time'),
                'value' => function($data) {return date('H:i:s', mktime(0, 0, $data->avg_time));},
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: center;'],
            ]
            /*
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
                ],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],*/
		],
	]);?>

</div>
