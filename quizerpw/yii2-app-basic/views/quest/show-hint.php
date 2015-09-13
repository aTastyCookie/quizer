<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Show Hint');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['quest/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="show-hint">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <?php echo $message?>
    </div>
</div>
