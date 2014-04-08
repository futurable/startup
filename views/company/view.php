<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

$this->title = 'Futural startup';
?>
<div class="site-index company-view">

	<?php if(isset($_GET['created'])) echo "<h1>".Yii::t('Company', 'Your company has been created!')."</h1>"; ?>

	<h2><?php echo $company->name; ?></h2>

	<h3><?php echo Yii::t('Company', 'Info'); ?></h3>
	<table>
	    <tr>
	        <th><?php echo Yii::t('Company', 'Business ID'); ?></th>
	        <td><?php echo $company->business_id; ?> </td>
	    </tr>
    	    <tr>
	        <th><?php echo Yii::t('Company', 'Employees'); ?></th>
	        <td><?php echo $company->employees; ?> </td>
	    </tr>
	    <tr>
	        <th><?php echo Yii::t('Company', 'Industry'); ?></th>
	        <td><?php echo Yii::t('Industry', $company->industry->name); ?></td>
	    </tr>
	    <tr>
	        <th><?php echo Yii::t('Company', 'Description'); ?></th>
	        <td><?php echo Yii::t('Industry', $company->industry->description); ?></td>
	    </tr>
	    <tr>
 
	    </tr>
	</table>

	<h3><?php echo Yii::t('Company', 'Cost-benefit calculation'); ?></h3>
	<table>
		<tr>
			<th></th>
			<th><?php echo(Yii::t('Company', 'Monthly')); ?></th>
			<th><?php echo(Yii::t('Company', 'Yearly')); ?></th>
		</tr>
		
	<?php
	    foreach($company->costbenefitCalculations[0]->costbenefitItems as $item){
	        $type = $item->costbenefitItemType;
	        $typeName = ucfirst($type->name);
	        $item->yearlyValue = $item->value*12;
	        
	        $tableRow = "
	            <tr>
	                <td>".Yii::t('Company', $typeName)."</td>
	                <td>{$item->value}&euro;</td>
	                <td>{$item->yearlyValue} &euro;</td>
	            </tr>";
	        
	        echo $tableRow;
	    }
	?>
	</table>
	
	<?php
		echo "<h3>".Yii::t('Company', 'What should you do next?')."</h3>";
		echo "<p>".Yii::t('Company', 'The learning environment').": <a href='https://futurality.fi'>futurality.fi</a></p>";
		echo "<p>".Yii::t('Company', 'The ERP system').": <a href='http://erp.futurality.fi/?db=$company->tag'>erp.futurality.fi</a></p>";
		echo "<p>".Yii::t('Company', 'The bank').": <a href='http://futurality.fi/bank/index.php/user/login/?company=$company->tag'>futurality.fi/bank</a></p>";
	?>
</div>
