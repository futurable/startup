<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "token_customer".
 *
 * @property integer $id
 * @property string $tag
 * @property string $name
 * @property string $street
 * @property integer $postal_code
 * @property string $city
 * @property string $phone
 * @property string $email
 *
 * @property Company[] $companies
 * @property OrderSetup[] $orderSetups
 * @property TokenKey[] $tokenKeys
 * @property TokenSetup[] $tokenSetups
 * @property User[] $users
 */
class TokenCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'name'], 'required'],
            [['postal_code'], 'integer'],
            [['tag'], 'string', 'max' => 32],
            [['name', 'street', 'city', 'email'], 'string', 'max' => 256],
            [['phone'], 'string', 'max' => 128],
            [['tag'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag' => 'Tag',
            'name' => 'Name',
            'street' => 'Street',
            'postal_code' => 'Postal Code',
            'city' => 'City',
            'phone' => 'Phone',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['token_customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderSetups()
    {
        return $this->hasMany(OrderSetup::className(), ['token_customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokenKeys()
    {
        return $this->hasMany(TokenKey::className(), ['token_customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokenSetups()
    {
        return $this->hasMany(TokenSetup::className(), ['token_customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['token_customer_id' => 'id']);
    }
}
