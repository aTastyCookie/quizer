<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

	  <?php $this->head() ?>

	<!--<script type="text/javascript" src="/js/jquery-1.9.0-min.js"></script>-->
	<script type="text/javascript" src="/js/run.js"></script>

</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Quizer',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Главная', 'url' => ['/site/index']],
                    ['label' => 'Профайл', 'url' => ['/user/settings/profile'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Квесты', 'url' => ['/quest/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Войти', 'url' => ['/user/security/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Выйти ('.Yii::$app->user->identity->username.')',
                        'url' => ['/user/security/logout'],
						'linkOptions' => ['data-method' => 'post'],
                        'visible' => !Yii::$app->user->isGuest
                    ],
					['label' => 'Регистрация', 'url' => ['/user/registration/register'], 'visible' => Yii::$app->user->isGuest]
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
