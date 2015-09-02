<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = Yii::t('app', 'Update Hint').': Попытка №'.$hint->attemp;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => '/quest'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes').' квеста: '.$quest->name, 'url' => ['index', 'quest_id' => $node->quest_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-hint-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_hint_form', [
        'hint' => $hint,
    ]) ?>

</div>
