<?php

namespace app\models;

/**
 * This is the model class for table "costbenefit_item_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $account
 *
 * @property CostbenefitItem[] $costbenefitItems
 */
class CostbenefitItemType extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'costbenefit_item_type';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['account'], 'integer'],
			[['name'], 'string', 'max' => 256],
			[['description'], 'string', 'max' => 1024]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'account' => 'Account',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCostbenefitItems()
	{
		return $this->hasMany(CostbenefitItem::className(), ['costbenefit_item_type_id' => 'id']);
	}
}
