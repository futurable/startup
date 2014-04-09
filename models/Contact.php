<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $organization
 * @property string $position
 * @property integer $company_id
 *
 * @property Company $company
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'organization', 'position'], 'required'],
            [['email'], 'email'],
            [['company_id'], 'integer'],
            [['name', 'organization', 'position'], 'string', 'max' => 512],
            [['email'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('Contact', 'ID'),
            'name' => Yii::t('Contact', 'Name'),
            'email' => Yii::t('Contact', 'Email'),
            'organization' => Yii::t('Contact', 'Organization'),
            'position' => Yii::t('Contact', 'Position'),
            'company_id' => Yii::t('Contact', 'Company ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
