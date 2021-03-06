<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Futural startup';
?>

<?php 
	NavBar::begin([
		'brandLabel' => 'Futural startup',
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => [
			['label' => 'Language', 'items' => [
				['label' => 'Finnish', 'url' => Url::canonical().'?lang=fi'],
				['label' => 'English', 'url' => Url::canonical().'?lang=en'],
			]]
		],
	]);
	NavBar::end();
?>

<div class="site-index">

	<div class="jumbotron">
		<h1><?php echo Yii::t('Company', 'Welcome')."!"; ?></h1>

		<p class="lead"><?php echo Yii::t('Company', 'Please provide a token key'); ?>:</p>
		
		<?php
			$form = ActiveForm::begin([
				'id' => 'tokenkey-form'
			]);

			echo "<p>".$form->field($tokenKey, 'token_key')."</p>";
			echo Html::submitButton(Yii::t('Company', 'Validate'), ['class' => 'btn btn-primary']);
			ActiveForm::end();
		?>
	</div>

</div>
