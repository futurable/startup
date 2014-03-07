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
			[['value', 'costbenefit_calculation_id', 'costbenefit_item_type_id'], 'required'],
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
			'[monthly][turnover]value' => Yii::t('CostBenefitItem', 'Monthly turnover value'),
			'[monthly][expenses]value' => Yii::t('CostBenefitItem', 'Monthly expenses value'),
			'[monthly][salaries]value' => Yii::t('CostBenefitItem', 'Monthly salaries value'),
			'[monthly][sideExpenses]value' => Yii::t('CostBenefitItem', 'Monthly side expenses value'),
			'[monthly][loans]value' => Yii::t('CostBenefitItem', 'Monthly loans value'),
			'[monthly][rents]value' => Yii::t('CostBenefitItem', 'Monthly rents value'),
			'[monthly][communication]value' => Yii::t('CostBenefitItem', 'Monthly communication value'),
			'[monthly][health]value' => Yii::t('CostBenefitItem', 'Monthly health value'),
			'[monthly][otherExpenses]value' => Yii::t('CostBenefitItem', 'Monthly other expenses value'),
			'[monthly][profit]value' => Yii::t('CostBenefitItem', 'Monthly profit'),
			'[yearly][turnover]value' => Yii::t('CostBenefitItem', 'Yearly turnover value'),
			'[yearly][expenses]value' => Yii::t('CostBenefitItem', 'Yearly expenses value'),
			'[yearly][salaries]value' => Yii::t('CostBenefitItem', 'Yearly salaries value'),
			'[yearly][sideExpenses]value' => Yii::t('CostBenefitItem', 'Yearly side expenses value'),
			'[yearly][loans]value' => Yii::t('CostBenefitItem', 'Yearly loans value'),
			'[yearly][rents]value' => Yii::t('CostBenefitItem', 'Yearly rents value'),
			'[yearly][communication]value' => Yii::t('CostBenefitItem', 'Yearly communication value'),
			'[yearly][health]value' => Yii::t('CostBenefitItem', 'Yearly health value'),
			'[yearly][otherExpenses]value' => Yii::t('CostBenefitItem', 'Yearly other expenses value'),
			'[yearly][profit]value' => Yii::t('CostBenefitItem', 'Yearly profit'),
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
