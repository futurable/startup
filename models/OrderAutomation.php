<?php

namespace app\models;

/**
 * This is the model class for table "order_automation".
 *
 * @property integer $id
 * @property string $create_date
 * @property string $year
 * @property integer $week
 *
 * @property Order[] $orders
 */
class OrderAutomation extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'order_automation';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['create_date', 'year'], 'safe'],
			[['year'], 'required'],
			[['week'], 'integer']
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
			'year' => 'Year',
			'week' => 'Week',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getOrders()
	{
		return $this->hasMany(Order::className(), ['order_automation_id' => 'id']);
	}
}
