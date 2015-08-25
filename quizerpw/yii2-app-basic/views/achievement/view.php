<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = Yii::t('app', 'View Achievement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Achievements'), 'url' => ['achievement/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="achievement-view">

    <h1><?php echo $this->title?></h1>

    <p>
        <?php echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $achiv->achievement_id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $achiv->achievement_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $achiv,
        'attributes' => [
            'achievement_id',
            'name',
            'description',
        ],
    ]) ?>

</div>
