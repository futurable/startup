<?php

namespace app\models;

/**
 * This is the model class for table "costbenefit_calculation".
 *
 * @property integer $id
 * @property string $create_date
 * @property integer $company_id
 *
 * @property Company $company
 * @property CostbenefitItem[] $costbenefitItems
 */
class CostbenefitCalculation extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'costbenefit_calculation';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['create_date'], 'safe'],
			[['company_id'], 'required'],
			[['company_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'create_date' => 'Create Date',
			'company_id' => 'Company ID',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCompany()
	{
		return $this->hasOne(Company::className(), ['id' => 'company_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCostbenefitItems()
	{
		return $this->hasMany(CostbenefitItem::className(), ['costbenefit_calculation_id' => 'id']);
	}
}
