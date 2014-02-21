<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\CostbenefitItem;
use app\commands\CBCTableRow;
$this->title = 'Futural startup - '.Yii::t('Company', 'Create a company');
?>
<div class="site-index">

	<div class="jumbotron">
		<h1><?php echo Yii::t('Company', 'Create a company'); ?></h1>

		<?php
			$form = ActiveForm::begin([
				'id' => 'create-form'
				, 'type' => ActiveForm::TYPE_INLINE
			]);

			echo $form->errorSummary($company);
		?>
		
		<div class="form-group">
			<h2><?php echo Yii::t('Company', 'Info'); ?></h2>
				
			<?php echo $form->field($company, 'name'); ?><br/>
			<?php echo $form->field($company, 'email'); ?><br/>
			<?php echo $form->field($company, 'industry_id'); ?><br/>
			
			<?php
				# TODO: tie this to the industry
				$employeeAmount = array_combine( range(1,10), range(1,10) ) + array_combine( range(10,100,10), range(10,100,10) );
			    
			    echo $form->field($company, 'employees')
		        ->dropDownList(
		            $employeeAmount
		        );		
			?><br/>
		</div>
	
		<div class="form-group">
			<table id='costBenefitCalculationTable'>
				<tr>
					<th></th>
					<th><?php echo  Yii::t('Company', 'Monthly') ?> (&euro;)</th>
				  	<th><?php echo  Yii::t('Company', 'Yearly') ?> (&euro;)</th>
					<th></th>
				</tr>
				<?php
					echo "<h2>".Yii::t('Company', 'Cost-benefit calculation')."</h2><br/>";
					foreach($costBenefitItemTypes as $costBenefitItemType){
						echo CBCTableRow::getRow($form, $costBenefitItemType); 
					}
				?>
			</table>
		</div>
		
		<p>	
			<div class="form-group">
				<?php echo Html::submitButton(Yii::t('Company', 'Create'), ['class' => 'btn btn-success']); ?>
			</div>
		</p>
		<?php ActiveForm::end(); ?>
	</div>
	
</div>
