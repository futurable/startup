<?php
namespace app\commands;

use Yii;
use yii\widgets\ActiveField;
use app\models\CostbenefitItem;

class CBCTableRow{
    public function getRow($form, $object)
    {
    	$monthlyItem = new CostbenefitItem();
    	$yearlyItem = new CostbenefitItem();
    	
    	$name = $object->name;
    	$label = ucfirst( Yii::t('CostBenefitItem', $name) );
    	$monthlyId = 'CostbenefitItem_'.$object->name."_monthly";
    	$yearlyId = 'CostbenefitItem_'.$object->name."_yearly";
    	
    	// Get the values
    	if(isset($_POST['CostbenefitItem'])){
	    	$monthlyItem->value = $_POST['CostbenefitItem']['monthly'][$name]['value'];
	    	$yearlyItem->value = $_POST['CostbenefitItem']['yearly'][$name]['value'];
    	}
    	
    	$tooltip = Yii::t('CostBenefitItem', 'Tooltip'.ucfirst($name));
    	
    	$monthlyValue = $form->field($monthlyItem, "[monthly][{$name}]value", [
			'options'=>['title' => $tooltip, 'id' => $monthlyId]
			, 'addon' => ['append' => ['content'=>'&euro;']]
		]);

    	
    	$yearlyValue = $form->field($yearlyItem, "[yearly][{$name}]value", [
			'options'=> ['title' => $tooltip, 'id' => $yearlyId]
			, 'addon' => ['append' => ['content'=>'&euro;']]
		]);
    	
		$html = "
			<tr>
				<td>
					$label
				</td>
				<td>
					$monthlyValue
				</td>
				<td>
					$yearlyValue
				</td>
			</tr>";
		
    	
    	return $html;
    }
}
