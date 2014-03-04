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
$this->title = 'Futural startup - '.Yii::t('Company', 'Create a company');

$industrySetupJSON = json_encode($industrySetupArray);
$industrySetupJS = "var IndustrySetupArray = $industrySetupJSON;\n";

$this->registerJs($industrySetupJS, View::POS_HEAD);
// TODO: fix this
//$this->registerJs('js/costBenefitCalculation.js');
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
		
		<div class="form-group" id='company-info'>
			<h2><?php echo Yii::t('Company', 'Info'); ?></h2>
				
			<?php echo $form->field($company, 'name'); ?><br/>
			<?php echo $form->field($company, 'email'); ?><br/>
			
			<?php	    
			    echo $form->field($company, 'industry_id')
		        ->dropDownList(
		            $industryArray,
					['prompt' => "- ".Yii::t('Company', 'Select industry')." -"]
		        );
			?><br/>	

			<?php
			    echo $form->field($company, 'employees')
		        ->dropDownList(
		            $employeeArray
		        );		
			?><br/>
		</div>
	
		<div class="form-group">
			<table id='costBenefitCalculationTable'>
				<tr>
					<th></th>
					<th><?php echo Yii::t('Company', 'Monthly') ?></th>
				  	<th><?php echo Yii::t('Company', 'Yearly') ?></th>
					<th></th>
				</tr>
				<?php
					echo "<h2>".Yii::t('Company', 'Cost-benefit calculation')."</h2><br/>";
					foreach($costBenefitItemTypes as $costBenefitItemType){
						echo CBCTableRow::getRow($form, $costBenefitItemType); 
					}
				?>
				<?php
					$profit = new $costBenefitItemType();
					$profit->name = 'profit';
					echo CBCTableRow::getRow($form, $profit); 
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
