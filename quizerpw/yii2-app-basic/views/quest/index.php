<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quest-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Quest'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'complexity',
            'url:url',
            // 'short',
            // 'descr:ntext',
            // 'date_start',
            // 'date_finish',
            // 'password',

			[
				'attribute' => 'Nodes',
				'format' => 'raw',
				'value' => function ($model) {   
								$html = '<div>';
								$count = $model->getNodeCount();
								if ($count > 0) {
									$html .= '<a href="/node/?quest_id=' . $model->id . '"'
										  . '>' . $count . ' nodes</a><br/>';  
									$html .= '<a href="/quest/visual/?quest_id=' . $model->id . '"'
										  . '>Visual</a><br/>';  
								}
								$html .= '<a href="/node/create?quest_id=' 
										. $model->id . '">Create node</a></div>';

								return $html;
				},
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
