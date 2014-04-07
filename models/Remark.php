<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remark".
 *
 * @property integer $id
 * @property string $description
 * @property string $event_date
 * @property string $create_date
 * @property integer $significance
 * @property integer $company_id
 *
 * @property Company $company
 */
class Remark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'remark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_date', 'create_date', 'company_id'], 'required'],
            [['event_date', 'create_date'], 'safe'],
            [['significance', 'company_id'], 'integer'],
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
            'description' => 'Description',
            'event_date' => 'Event Date',
            'create_date' => 'Create Date',
            'significance' => 'Significance',
            'company_id' => 'Company ID',
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
