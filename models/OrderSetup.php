<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_setup".
 *
 * @property integer $id
 * @property string $type
 * @property integer $amount
 * @property integer $rows
 * @property string $weight
 * @property integer $token_customer_id
 * @property string $create_date
 * @property string $alter_date
 *
 * @property Order[] $orders
 * @property OrderFactor[] $orderFactors
 * @property TokenCustomer $tokenCustomer
 */
class OrderSetup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_setup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['amount', 'rows', 'token_customer_id'], 'integer'],
            [['weight'], 'number'],
            [['token_customer_id'], 'required'],
            [['create_date', 'alter_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'rows' => 'Rows',
            'weight' => 'Weight',
            'token_customer_id' => 'Token Customer ID',
            'create_date' => 'Create Date',
            'alter_date' => 'Alter Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['order_setup_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFactors()
    {
        return $this->hasMany(OrderFactor::className(), ['order_setup_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokenCustomer()
    {
        return $this->hasOne(TokenCustomer::className(), ['id' => 'token_customer_id']);
    }
}
