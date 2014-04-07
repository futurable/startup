<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "industry".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * @property Company[] $companies
 * @property IndustrySetup[] $industrySetups
 */
class Industry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'industry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['industry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrySetups()
    {
        return $this->hasMany(IndustrySetup::className(), ['industry_id' => 'id']);
    }
}
