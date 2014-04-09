<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\CostbenefitItem;
use app\commands\CBCTableRow;
use yii\web\View;
use yii\helpers\Json;
use yii\web\JqueryAsset;

$this->title = 'Futural startup - '.Yii::t('Contact', 'Your information');

?>
<div class="site-index">

	<div class="jumbotron">
		<h1><?php echo Yii::t('Contact', 'Your information'); ?></h1>

		<?php
			$form = ActiveForm::begin([
				'id' => 'contact-form',
			]);

			echo $form->errorSummary($contact);
		?>
		
		<div class="form-group" id='contact-info'>
			<?php echo Html::activeHiddenInput($tokenKey, 'token_key'); ?>
			
			<?php echo $form->field($contact, 'name'); ?>
			<?php echo $form->field($contact, 'email'); ?>
			<?php echo $form->field($contact, 'organization'); ?>
			<?php echo $form->field($contact, 'position'); ?>
			
		</div>
	
		
		<p>	
			<div class="form-group">
				<?php echo Html::submitButton(Yii::t('Contact', 'Continue'), ['class' => 'btn btn-success']); ?>
			</div>
		</p>
		<?php ActiveForm::end(); ?>
	</div>
	
</div>
