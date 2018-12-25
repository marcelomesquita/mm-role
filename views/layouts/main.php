<?php

/**
 * @var $this \yii\web\View
 * @var $content string
 */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$this->registerJs("
	$('[data-toggle=\"tooltip\"]').tooltip();
	$('[data-toggle=\"popover\"]').popover();
");

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?= Html::csrfMetaTags() ?>

	<link rel="shortcut icon" href="<?= Yii::$aliases['@web']; ?>/favicon.ico">

	<title><?= Html::encode($this->title) ?> | <?= Yii::$app->name ?></title>

	<?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody() ?>

	<div class="wrap">
		<?php
			NavBar::begin([
				'brandLabel' => Yii::$app->name,
				'brandUrl' => Yii::$app->homeUrl,
				'options' => [
					'class' => 'navbar-inverse',
				],
			]);

			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [
					['label' => 'Clientes', 'url' => ['/client/index'], 'visible' => !Yii::$app->user->isGuest],
					['label' => 'Usuários', 'url' => ['/user/index'], 'visible' => !Yii::$app->user->isGuest],
					Yii::$app->user->isGuest
						? ['label' => 'Login', 'url' => ['/site/login']]
						: ['label' => Yii::$app->user->identity->name, 'url' => '#', 'items' => [
							['label' => 'Perfil', 'url' => ['user/profile']],
							['label' => 'Logout', 'url' => ['site/logout'], 'linkOptions' => ['data' => ['method' => 'post', 'confirm' => 'Deseja realmente sair?']]],
						]],
				],
			]);

			NavBar::end();
		?>

		<div class="container">
			<?= Breadcrumbs::widget(['homeLink' => ['label' => '<i class="glyphicon glyphicon-home"></i>', 'url' => ['/']], 'encodeLabels' => false, 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
			<?= Alert::widget() ?>
			<?= $content ?>
		</div>
	</div>

	<footer class="footer">
		<div class="container">
			<p><?= Yii::$app->name ?> &copy; <?= date('Y') ?></p>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
