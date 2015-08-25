<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = Yii::t('app', 'Update Node').': '.$node->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['quest/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index', 'quest_id' => Yii::$app->getRequest()->get('quest_id')]];
$this->params['breadcrumbs'][] = ['label' => $node->name, 'url' => ['view', 'id' => $node->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="node-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'node' => $node,
    ]) ?>

</div>
