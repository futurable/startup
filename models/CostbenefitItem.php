<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "costbenefit_item".
 *
 * @property integer $id
 * @property string $value
 * @property integer $costbenefit_calculation_id
 * @property integer $costbenefit_item_type_id
 *
 * @property CostbenefitCalculation $costbenefitCalculation
 * @property CostbenefitItemType $costbenefitItemType
 */
class CostbenefitItem extends \yii\db\ActiveRecord
{
	public $yearlyValue;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'costbenefit_item';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['value', 'yearlyValue', 'costbenefit_calculation_id', 'costbenefit_item_type_id'], 'required'],
			[['value', 'yearlyValue'], 'number', 'message'=>'Please enter a value for {attribute}.'],
			[['costbenefit_calculation_id', 'costbenefit_item_type_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'value' => Yii::t('CostBenefitItem', 'Value'),
			'[turnover][monthly]value' => Yii::t('CostBenefitItem', 'Monthly turnover value'),
			'[expenses][monthly]value' => Yii::t('CostBenefitItem', 'Monthly expenses value'),
			'[salaries][monthly]value' => Yii::t('CostBenefitItem', 'Monthly salaries value'),
			'[sideExpenses][monthly]value' => Yii::t('CostBenefitItem', 'Monthly side expenses value'),
			'[loans][monthly]value' => Yii::t('CostBenefitItem', 'Monthly loans value'),
			'[rents][monthly]value' => Yii::t('CostBenefitItem', 'Monthly rents value'),
			'[communication][monthly]value' => Yii::t('CostBenefitItem', 'Monthly communication value'),
			'[health][monthly]value' => Yii::t('CostBenefitItem', 'Monthly health value'),
			'[otherExpenses][monthly]value' => Yii::t('CostBenefitItem', 'Monthly other expenses value'),
			'[profit][monthly]value' => Yii::t('CostBenefitItem', 'Monthly profit'),
			'[turnover][yearly]value' => Yii::t('CostBenefitItem', 'Yearly turnover value'),
			'[expenses][yearly]value' => Yii::t('CostBenefitItem', 'Yearly expenses value'),
			'[salaries][yearly]value' => Yii::t('CostBenefitItem', 'Yearly salaries value'),
			'[sideExpenses][yearly]value' => Yii::t('CostBenefitItem', 'Yearly side expenses value'),
			'[loans][yearly]value' => Yii::t('CostBenefitItem', 'Yearly loans value'),
			'[rents][yearly]value' => Yii::t('CostBenefitItem', 'Yearly rents value'),
			'[communication][yearly]value' => Yii::t('CostBenefitItem', 'Yearly communication value'),
			'[health][yearly]value' => Yii::t('CostBenefitItem', 'Yearly health value'),
			'[otherExpenses][yearly]value' => Yii::t('CostBenefitItem', 'Yearly other expenses value'),
			'[profit][yearly]value' => Yii::t('CostBenefitItem', 'Yearly profit'),
			'yearlyValue' => Yii::t('CostBenefitItem', 'Yearly value'),
			'costbenefit_calculation_id' => 'Costbenefit Calculation ID',
			'costbenefit_item_type_id' => 'Costbenefit Item Type ID',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCostbenefitCalculation()
	{
		return $this->hasOne(CostbenefitCalculation::className(), ['id' => 'costbenefit_calculation_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCostbenefitItemType()
	{
		return $this->hasOne(CostbenefitItemType::className(), ['id' => 'costbenefit_item_type_id']);
	}
}
