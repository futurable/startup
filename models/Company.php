<?php

namespace app\models;

use Yii;
use Symfony\Component\Finder\Expression\Expression;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\commands\CreateCompanyTag;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $tag
 * @property string $business_id
 * @property string $email
 * @property integer $employees
 * @property integer $active
 * @property string $create_time
 * @property string $bank_account_created
 * @property string $openerp_database_created
 * @property integer $token_key_id
 * @property integer $industry_id
 * @property integer $token_customer_id
 *
 * @property TokenKey $tokenKey
 * @property Industry $industry
 * @property TokenCustomer $tokenCustomer
 * @property CompanyPasswords[] $companyPasswords
 * @property CostbenefitCalculation[] $costbenefitCalculations
 * @property Order[] $orders
 * @property Remark $remark
 * @property Salary[] $salaries
 */
class Company extends \yii\db\ActiveRecord
{
	public $profit;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'company';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'tag', 'business_id', 'email', 'employees', 'token_key_id', 'industry_id'], 'required'],
			[['email'], 'email'],
			[['name'], 'unique'],
			[['name'], 'validateTag'],
			[['name'], 'string', 'min'=>3],
			[['employees', 'active', 'token_key_id', 'industry_id'], 'integer'],
			[['create_time'], 'safe'],
			[['name', 'email'], 'string', 'max' => 256],
			[['tag'], 'string', 'max' => 32],
			[['business_id'], 'string', 'max' => 9],
		];
	}

	public function validateTag($attribute, $params)
	{
		// Create the tag
		$customer = $this->tokenKey->tokenCustomer;
		$tagCreator = new CreateCompanyTag();
		$tag = $tagCreator->run($this->name, $customer->tag);
	
		// See if the tag is already used
		$record = Company::find()
		->select('tag')
		->where('tag=:tag')
		->addParams(['tag'=>$tag])
		->one();
	
		if($record){
			$this->addError($attribute,
					Yii::t('Company', 'This company name is already taken.'). " " . Yii::t('Company', 'Please select another one').".");
			$return = false;
		} else {
			$return = true;
		}
	
		return $record;
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('Company', 'ID'),
			'name' => Yii::t('Company', 'Company name'),
			'tag' => Yii::t('Company', 'Tag'),
			'business_id' => Yii::t('Company', 'Business ID'),
			'email' => Yii::t('Company', 'Email'),
			'employees' => Yii::t('Company', 'Employees'),
			'active' => Yii::t('Company', 'Active'),
			'profit' => Yii::t('Company', 'Profit'),
			'create_time' => Yii::t('Company', 'Create Time'),
			'token_key_id' => Yii::t('Company', 'Token key'),
			'industry_id' => Yii::t('Company', 'Industry'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTokenKey()
	{
		return $this->hasOne(TokenKey::className(), ['id' => 'token_key_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getIndustry()
	{
		return $this->hasOne(Industry::className(), ['id' => 'industry_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTokenCustomer()
	{
		return $this->hasOne(TokenCustomer::className(), ['id' => 'token_customer_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompanyPasswords()
	{
		return $this->hasOne(CompanyPasswords::className(), ['company_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCostbenefitCalculations()
	{
		return $this->hasMany(CostbenefitCalculation::className(), ['company_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders()
	{
		return $this->hasMany(Order::className(), ['company_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRemark()
	{
		return $this->hasOne(Remark::className(), ['company_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSalaries()
	{
		return $this->hasMany(Salary::className(), ['company_id' => 'id']);
	}
}
