<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Futural startup';
?>
<div class="site-index">

	<div class="jumbotron">
		<h1><?php echo Yii::t('Company', 'Welcome')."!"; ?></h1>

		<p class="lead"><?php echo Yii::t('Company', 'Please provide a token key'); ?>:</p>
		
		<?php	
			$form = ActiveForm::begin(['id' => 'tokenkey-form']);
			echo $form->field($tokenKey, 'token_key');
			echo Html::submitButton(Yii::t('Company', 'Validate'), ['class' => 'btn btn-success']);
			ActiveForm::end();
		?>
	</div>
	
</div>
