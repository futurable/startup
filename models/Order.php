<?php

namespace app\models;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $created
 * @property string $event_time
 * @property string $executed
 * @property string $sent
 * @property integer $rows
 * @property string $value
 * @property integer $active
 * @property integer $openerp_purchase_order_id
 * @property integer $company_id
 * @property integer $order_setup_id
 * @property integer $order_automation_id
 *
 * @property Company $company
 * @property OrderAutomation $orderAutomation
 * @property OrderSetup $orderSetup
 */
class Order extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'order';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['created', 'event_time', 'executed', 'sent'], 'safe'],
			[['rows', 'active', 'openerp_purchase_order_id', 'company_id', 'order_setup_id', 'order_automation_id'], 'integer'],
			[['value'], 'number'],
			[['openerp_purchase_order_id', 'company_id', 'order_setup_id', 'order_automation_id'], 'required']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'created' => 'Created',
			'event_time' => 'Event Time',
			'executed' => 'Executed',
			'sent' => 'Sent',
			'rows' => 'Rows',
			'value' => 'Value',
			'active' => 'Active',
			'openerp_purchase_order_id' => 'Openerp Purchase Order ID',
			'company_id' => 'Company ID',
			'order_setup_id' => 'Order Setup ID',
			'order_automation_id' => 'Order Automation ID',
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
	public function getOrderAutomation()
	{
		return $this->hasOne(OrderAutomation::className(), ['id' => 'order_automation_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getOrderSetup()
	{
		return $this->hasOne(OrderSetup::className(), ['id' => 'order_setup_id']);
	}
}
