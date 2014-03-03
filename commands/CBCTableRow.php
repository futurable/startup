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
    	$tooltip = Yii::t('CostBenefitItem', 'Tooltip'.ucfirst($object->name));
    	$monthlyValue = $form->field($item, 'value', ['options'=>['title' => $tooltip]]);
    	$yearlyValue = $form->field($item, 'yearlyValue', ['options'=>['title' => $tooltip]]);
    	
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
