<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "costbenefit_item_type".
 *
 * @property integer $id
 * @property integer $order
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
            [['order', 'name'], 'required'],
            [['order', 'account'], 'integer'],
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
            'order' => 'Order',
            'name' => 'Name',
            'description' => 'Description',
            'account' => 'Account',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostbenefitItems()
    {
        return $this->hasMany(CostbenefitItem::className(), ['costbenefit_item_type_id' => 'id']);
    }
}
