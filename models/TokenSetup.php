<?php

namespace app\models;

/**
 * This is the model class for table "token_setup".
 *
 * @property integer $id
 * @property string $description
 * @property integer $create_init_data
 * @property integer $create_demo_data
 * @property integer $create_purchasing_orders
 * @property integer $token_customer_id
 * @property integer $industries
 *
 * @property TokenKey[] $tokenKeys
 * @property TokenCustomer $tokenCustomer
 */
class TokenSetup extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'token_setup';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['description', 'token_customer_id', 'industries'], 'required'],
			[['create_init_data', 'create_demo_data', 'create_purchasing_orders', 'token_customer_id', 'industries'], 'integer'],
			[['description'], 'string', 'max' => 32]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'description' => 'Description',
			'create_init_data' => 'Create Init Data',
			'create_demo_data' => 'Create Demo Data',
			'create_purchasing_orders' => 'Create Purchasing Orders',
			'token_customer_id' => 'Token Customer ID',
			'industries' => 'Industries',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getTokenKeys()
	{
		return $this->hasMany(TokenKey::className(), ['token_setup_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getTokenCustomer()
	{
		return $this->hasOne(TokenCustomer::className(), ['id' => 'token_customer_id']);
	}
}
