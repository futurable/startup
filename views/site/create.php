<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Futural startup - '.Yii::t('Company', 'Create a company');
?>
<div class="site-index">

	<div class="jumbotron">
		<h1><?php echo Yii::t('Company', 'Create a company'); ?></h1>

		<?php	
			$form = ActiveForm::begin([
				'id' => 'create-form'
				, 'type' => ActiveForm::TYPE_HORIZONTAL
			]);

			echo $form->field($company, 'name');
			echo $form->field($company, 'email');
			echo $form->field($company, 'industry');
			echo $form->field($company, 'employees');
			
			echo Html::submitButton(Yii::t('Company', 'Create'), ['class' => 'btn btn-success']);
			ActiveForm::end();
		?>
	</div>
	
</div>
