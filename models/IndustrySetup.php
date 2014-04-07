<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "industry_setup".
 *
 * @property integer $id
 * @property integer $turnover
 * @property integer $minimum_wage_rate
 * @property integer $average_wage_rate
 * @property integer $maximum_wage_rate
 * @property integer $rents
 * @property integer $communication
 * @property integer $industry_id
 *
 * @property Industry $industry
 */
class IndustrySetup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'industry_setup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['turnover', 'minimum_wage_rate', 'rents', 'communication', 'industry_id'], 'required'],
            [['turnover', 'minimum_wage_rate', 'average_wage_rate', 'maximum_wage_rate', 'rents', 'communication', 'industry_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'turnover' => 'Turnover',
            'minimum_wage_rate' => 'Minimum Wage Rate',
            'average_wage_rate' => 'Average Wage Rate',
            'maximum_wage_rate' => 'Maximum Wage Rate',
            'rents' => 'Rents',
            'communication' => 'Communication',
            'industry_id' => 'Industry ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'industry_id']);
    }
}
