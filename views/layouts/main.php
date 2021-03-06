<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\widgets\ActiveForm;
use app\models\Lang;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= Html::encode($this->title) ?></title>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="<?= Yii::getAlias('@web') ?>/css/img/favicon.ico" />
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
	<div class="wrap">
		<div class='disclaimer'>
		    <p>
		        Welcome to Futural - a virtual learning environment 
		        by <a href='http://futurable.fi'>Futurable</a>.
		        <a href='mailto:futurality@futurable.fi?subject=Feedback'>Give feedback</a>.
		    </p>
		</div>
            
		<div class="container">
			<div id="logo">
            	<?php echo Html::img(Yii::getAlias('@web').'/css/img/futural-logo-startup_h128.png'); ?>
	        </div>
	        
			<?= $content ?>
		</div>
	</div>

	<footer class="footer">
		<div class="container">
			<p class="pull-left">Futural business simulation environment</p>
			<p class="pull-right">Futurable Oy <?= date('Y') ?></p>
		</div>
	</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
