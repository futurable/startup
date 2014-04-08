<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $role
 * @property integer $token_customer_id
 *
 * @property TokenCustomer $tokenCustomer
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'token_customer_id'], 'required'],
            [['role', 'token_customer_id'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 512],
            [['email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'role' => 'Role',
            'token_customer_id' => 'Token Customer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokenCustomer()
    {
        return $this->hasOne(TokenCustomer::className(), ['id' => 'token_customer_id']);
    }
}
