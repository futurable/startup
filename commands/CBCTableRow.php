<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\widgets\ActiveField;
use app\models\CostbenefitItem;

class CBCTableRow extends Controller
{
    public function getRow($form, $object)
    {
    	$item = new CostbenefitItem();
    	
    	$label = ucfirst( Yii::t('CostBenefitItem', $object->name) );
    	$monthlyId = 'CostbenefitItem_'.$object->name."_monthly";
    	$yearlyId = 'CostbenefitItem_'.$object->name."_yearly";
    	
    	$tooltip = Yii::t('CostBenefitItem', 'Tooltip'.ucfirst($object->name));
    	
    	$monthlyValue = $form->field($item, 'value', ['options'=>['title' => $tooltip, 'id' => $monthlyId]]);
    	$yearlyValue = $form->field($item, 'yearlyValue', ['options'=>['title' => $tooltip, 'id' => $yearlyId]]);
    	
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
