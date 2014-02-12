<?php

namespace app\models;

/**
 * This is the model class for table "salary".
 *
 * @property integer $id
 * @property string $create_date
 * @property integer $employees
 * @property string $amount
 * @property integer $week
 * @property integer $bank_transaction_id
 * @property integer $company_id
 * @property string $year
 *
 * @property Company $company
 */
class Salary extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'salary';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['create_date', 'year'], 'safe'],
			[['employees', 'week', 'bank_transaction_id', 'company_id'], 'integer'],
			[['amount'], 'number'],
			[['company_id'], 'required']
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
			'employees' => 'Employees',
			'amount' => 'Amount',
			'week' => 'Week',
			'bank_transaction_id' => 'Bank Transaction ID',
			'company_id' => 'Company ID',
			'year' => 'Year',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCompany()
	{
		return $this->hasOne(Company::className(), ['id' => 'company_id']);
	}
}
