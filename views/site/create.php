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
$this->title = 'Futural startup - '.Yii::t('Company', 'Create a company');

$industrySetupJSON = json_encode($industrySetupArray);
$industrySetupJS = "var IndustrySetupArray = $industrySetupJSON;\n";

$this->registerJs($industrySetupJS, View::POS_HEAD);
$this->registerJsFile('js/costBenefitCalculation.js', JqueryAsset::className());
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
			<?php echo Html::activeHiddenInput($tokenKey, 'token_key'); ?>
			
			<label for='company-name'><?php echo Yii::t('Company', 'Company name'); ?></label><br/>
			<?php echo $form->field($company, 'name', ['options'=>['title'=>Yii::t('Company', 'The company name. Be creative and stand out!')]]); ?>
			
			<label for='company-email'><?php echo Yii::t('Company', 'Email'); ?></label><br/>
			<?php echo $form->field($company, 'email', ['options'=>['title'=>Yii::t('Company', 'The company email. You will receive the account information here.')]]); ?>
			
			<label for='company-industry_id'><?php echo Yii::t('Company', 'Industry'); ?></label><br/>
			<?php
			    echo $form->field($company, 'industry_id')
		        ->dropDownList(
		            $industryArray,
					['prompt' => "- ".Yii::t('Company', 'Select industry')." -"]
		        );
			?><br/>	

			<label for='company-employees'><?php echo Yii::t('Company', 'Employees'); ?></label><br/>
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
					$CBCTR = new CBCTableRow();
					foreach($costBenefitItemTypes as $costBenefitItemType){
						echo $CBCTR->getRow($form, $costBenefitItemType);
					}
				?>
				<?php
					$profit = new $costBenefitItemType();
					$profit->name = 'profit';
					echo $CBCTR->getRow($form, $profit); 
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
