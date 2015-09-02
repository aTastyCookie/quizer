<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\NodeHint;

$this->title = Yii::t('app', 'Node Hints').' к вопросу: '.$node->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => '/quest'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes').' квеста: '.$quest->name, 'url' => ['index', 'quest_id' => $node->quest_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .view-hint {
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

    .view-hint:hover {
        background-position: -20px 0;
    }

    .update-hint {
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

    .update-hint:hover {
        background-position: -20px 0;
    }

    .delete-hint {
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

    .delete-hint:hover {
        background-position: -20px 0;
    }
</style>

<div class="node-hints">

    <h1><?php echo Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('app', 'Create Hint'), ['node/createhint', 'id' => $node->id], ['class' => 'btn btn-success'])?>
    </p>


    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'hint_id',
            'attemp',
            [
                'attribute' => 'type',
                'value' => function($data) {
                    return NodeHint::$_transcript[$data->type];
                }
            ],
            'message',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $data, $key) {
                        return Html::a('', ['node/updatehint', 'id' => $data->hint_id], [
                            'class' => 'update-hint',
                            'title' => Yii::t('app', 'Update Hint')
                        ]);
                    },
                    'delete' => function ($url, $data, $key) {
                        return Html::a('', ['node/deletehint', 'id' => $data->hint_id], [
                            'class' => 'delete-hint',
                            'title' => Yii::t('app', 'Delete Hint'),
                            'onclick' => 'return confirm(\''.Yii::t('app', 'Are you sure?').'\') ? true : false;'
                        ]);
                    }
                ],
                'contentOptions' =>  ['style' => 'text-align: center;']
            ],
        ],
    ]); ?>

</div>
