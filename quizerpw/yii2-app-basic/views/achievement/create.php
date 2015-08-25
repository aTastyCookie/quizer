<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Node */

$this->title = Yii::t('app', 'Create Achievement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Achievements'), 'url' => ['achievement/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="achievement-create">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'achiv' => $achiv,
    ]) ?>

</div>
