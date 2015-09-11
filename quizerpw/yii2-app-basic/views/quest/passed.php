<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ARUser;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Passed Quests');
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
</style>

<div class="quest-index">
	<h1><?php echo Html::encode($this->title) ?></h1>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;'],
            ],
            [
                'attribute' => 'quest.name',
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'quest.complexity',
                'format' => 'raw',
                'value' => function($data) {
                    $html = '';

                    for($i = 1; $i <= 10; $i++) {
                        $html .= '<div class="jackal '.($data->quest->complexity >= $i ? 'check' : 'no').'"></div>';
                    }

                    return $html;
                },
                'headerOptions' => ['style' => 'text-align: center; width: 230px;'],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
			// 'short',
			// 'descr:ntext',
			// 'date_start',
			// 'date_finish',
			// 'password',

			[
				'attribute' => Yii::t('app', 'Nodes'),
				'format' => 'raw',
				'value' => function($data) {return $data->quest->getNodeCount().' шт.';},
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' =>  ['style' => 'text-align: right;']
			]
		],
	]); ?>

</div>
