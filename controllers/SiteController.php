<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Company;
use app\models\TokenKey;
use app\models\CostbenefitCalculation;
use app\models\CostbenefitItem;
use app\models\CostbenefitItemType;
use app\models\Industry;
use app\models\IndustrySetup;
use app\models\TokenCustomer;
use Symfony\Component\Console\Helper\Helper;
use app\commands\CBCTableRow;
use app\commands\TrimNonAlphaNumeric;
use cebe\markdown\Markdown;
use app\commands\CreateBusinessID;
use app\commands\CreateCompanyTag;
use yii\web\Session;
use app\models\CompanyPasswords;
use app\models\Contact;

class SiteController extends Controller
{

    private $tokenKey;

    public function behaviors()
    {
        return [];
    }

    function init()
    {
        parent::init();
        if (isset($_GET['lang'])) {
            \Yii::$app->language = $_GET['lang'];
            \Yii::$app->session['lang'] = \Yii::$app->language;
        } else 
            if (isset(\Yii::$app->session['lang'])) {
                \Yii::$app->session['lang'] = \Yii::$app->language;
            }
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ]
        ];
    }

    public function actionIndex()
    {
        $tokenKey = new TokenKey();
        $tokenKey->load($_POST);
        $action = 'index';

        // Token key has been sent
        if ($tokenKey->token_key) {
            $record = $this->getReclaimedTokenKey($tokenKey);

            // Check if the token key is valid
            if ($this->validateTokenKey($tokenKey) === true) {
                $models = $this->createCompanyModels();
                
                //if ($models['contact']['attributes']['name'] and ! $models['company']['attributes']['name']) {
                if ( isset( Yii::$app->request->post()['Contact'] ) && !isset( Yii::$app->request->post()['Contact']['id']) ) {
                    $contact = new Contact();
                    $contact->load(Yii::$app->request->post());
                    $contact->save();
                    
                    $models['contact'] = $contact;
                }
                
                if ($models['company']['attributes']['name']) {
                    if ($this->saveModels($models) === true) {
                        $action = 'save';
                    } else {
                        $tokenKey->addError('token_key', Yii::t("TokenKey", "Error while saving the company. Please report this incident to helpdesk@futurality.fi"));
                        $action = 'create';
                    }
                } else {
                    if ($models['tokenKey']['token_customer_id'] == 1 and ! $models['contact']['attributes']['name']) {
                        $action = 'contact';
                    } else
                        $action = 'create';
                }
            } elseif ($record == true) {
                // Token key is already used
                $tokenKey->addError('token_key', Yii::t('TokenKey', 'Token key is already used on {dateTime}.', [
                    'dateTime' => $record->reclaim_date
                ]));
            } else {
                // Invalid token key
                $tokenKey->addError('token_key', Yii::t('TokenKey', 'Invalid token key.'));
            }
        }

        // Token key is valid
        if ($action == 'contact') {
            return $this->render('contact', $models);
        }
        if ($action == 'create') {
            return $this->render('create', $models);
        }
        // Company has been saved
        if ($action == 'save') {
            $this->redirect([
                'company/index',
                'id' => $models['company']->id,
                'created' => 'true'
            ]);
        }
        // Token key has not been sent
        if ($action == 'index') {
            return $this->render('index', [
                'tokenKey' => $tokenKey
            ]);
        }
    }

    private function getTokenKeyIdFromTokenKey($tokenKey)
    {
        $record = TokenKey::find()->select([
            'id'
        ])
            ->where('token_key=:token_key')
            ->addParams([
            ':token_key' => $tokenKey->token_key
        ])
            ->one();
        
        return $record->id;
    }

    private function validateTokenKey($tokenKey)
    {
        $isValid = false;
        
        $record = TokenKey::find()->select([
            'token_key'
        ])
            ->where([
            'and',
            'token_key=:token_key',
            'reclaim_date IS NULL'
        ])
            ->addParams([
            ':token_key' => $tokenKey->token_key
        ])
            ->one();
        
        if (! $record)
            $isValid = false;
        else
            $isValid = true;
        
        return $isValid;
    }

    private function getReclaimedTokenKey($tokenKey)
    {
        $record = TokenKey::find()->where('token_key=:token_key')
            ->addParams([
            ':token_key' => $tokenKey->token_key
        ])
            ->one();
        
        return $record;
    }

    private function createCompanyModels()
    {
        $tokenKey = new TokenKey();
        $tokenKey->load($_POST);
        $tokenKey = TokenKey::find()->where('token_key=:token_key')
            ->addParams([
            ':token_key' => $tokenKey->token_key
            ])
            ->one();
        $contact = new Contact();
        if (isset( Yii::$app->request->post()['Contact']['id'] )) {
            $contact = Contact::find()->select('id,name,email,organization,position,create_time')->where( ['id' => Yii::$app->request->post()['Contact']['id']] )->one();
        }
        
        $company = new Company();
        $company->load($_POST);
        $company->token_key_id = $tokenKey->id;
        $company->token_customer_id = $tokenKey->token_customer_id;
        
        $industry = new Industry();
        $industry->load($_POST);
        
        $costBenefitCalculation = new CostbenefitCalculation();
        $costBenefitItemTypes = CostbenefitItemType::find()->orderBy('order')->all();
        
        $industryArray = $this->getIndustryArray($tokenKey);
        $industrySetupArray = $this->getIndustrySetupArray();
        $employeeArray = $this->getEmployeeArray($tokenKey);
        
        $render = [
            'tokenKey' => $tokenKey,
            'contact' => $contact,
            'company' => $company,
            'industry' => $industry,
            'industryArray' => $industryArray,
            'industrySetupArray' => $industrySetupArray,
            'employeeArray' => $employeeArray,
            'costBenefitCalculation' => $costBenefitCalculation,
            'costBenefitItemTypes' => $costBenefitItemTypes
        ];
        
        return $render;
    }

    private function getIndustryArray($tokenKey)
    {
        $industries = $tokenKey->tokenSetup->industries;
        
        if($industries != 0){
            $industryArray = explode(',', $industries);
            $record = Industry::find()->where(['in', 'id', $industryArray])->all();
        }
        // @TODO: get excluded industries from somewhere
        else $record = Industry::find()->where(['not', ['id' => '100']])->all();
        
        foreach ($record as $row) {
            $industryDropdown[$row->id] = Yii::t('Industry', $row->name);
        }
        
        return $industryDropdown;
    }

    private function getEmployeeArray($tokenKey)
    {
        $employees = $tokenKey->tokenSetup->employees;
        
        // No spesific settings is set
        if($employees == 0){
            $keys = range(10, 100, 10);
            $values = range(10, 100, 10);
            
            foreach ($values as $key => $value) {
                $values[$key] = $value . "+";
            }
            $combined = array_combine($keys, $values);
            
            $employeeDropdown = array_combine(range(1, 9), range(1, 9)) + $combined;
        }
        // The key has spesific settings
        else{
            $employeesArray = explode(',', $employees);
            $keys = $employeesArray;
            $values = $employeesArray;
            
            $employeeDropdown = array_combine($keys, $values);
        }
        
        return $employeeDropdown;
    }

    private function getIndustrySetupArray()
    {
        // Get industry setups
        $industrySetups = IndustrySetup::find()->all();
        foreach ($industrySetups as $industrySetup) {
            $industrySetupArray[$industrySetup->industry_id] = array(
                'turnover' => $industrySetup->turnover,
                'minWage' => $industrySetup->minimum_wage_rate,
                'avgWage' => $industrySetup->average_wage_rate,
                'maxWage' => $industrySetup->maximum_wage_rate,
                'rents' => $industrySetup->rents,
                'communication' => $industrySetup->communication
            );
        }
        return $industrySetupArray;
    }

    private function saveModels($models)
    {
        $success = false;
        
        $transaction = Yii::$app->db->beginTransaction();
        
        // Save the company
        $company = $models['company'];
        
        // Get token key id
        $tokenKey = $models['tokenKey'];
        
        $tokenKey = TokenKey::find()
            ->where('token_key=:token_key')
            ->addParams([
            ':token_key' => $tokenKey->token_key
        ])->one();
        
        $company->token_key_id = $tokenKey->id;
        
        // Get token customer tag
        $tokenCustomer = TokenCustomer::find()->select('tag')
            ->where('id=:token_customer_id')
            ->addParams([
            ':token_customer_id' => $tokenKey->token_customer_id
        ])->one();
        
        // Mark token key as used
        if (! $tokenKey->save())
            $modelsSaved[] = 'tokenKey';

        // Create a safe company tag
        $TagCreator = new CreateCompanyTag();
        $company->tag = $TagCreator->run($company->name, $tokenCustomer->tag);
        
        // Create business ID
        $BIDCreator = new CreateBusinessID();
        $company->business_id = $BIDCreator->run();
        
        // TODO: fix this
        $company->create_time = date('Y-m-d H:i:s');
        if (! $company->save())
            $modelsSaved[] = 'company';

        // Save the contact
        if (isset( Yii::$app->request->post()['Contact']['id'] )) {
            $contact = Contact::findOne( Yii::$app->request->post()['Contact']['id'] );
            if (! empty($contact)) {
                $contact->company_id = $company->id;
                $contact->save();
            }
        }
        
        // Create a company passwords row
        $companyPasswords = new CompanyPasswords();
        $companyPasswords->company_id = $company->id;
        if (! $companyPasswords->save())
            $modelsSaved[] = 'companyPasswords';
            
            // Create the cost-benefit calculation
        $CostbenefitCalculation = $models['costBenefitCalculation'];
        $CostbenefitCalculation->company_id = $company->id;
        if (! $CostbenefitCalculation->save())
            $modelsSaved[] = 'CostbenefitCalculation';
            
            // Create the cost-benefit calculation items
        foreach ($_POST['CostbenefitItem']['monthly'] as $key => $item) {
            // Fetch the id
            $record = CostbenefitItemType::find()->select('id')
                ->where('name=:name')
                ->addParams([
                ':name' => $key
            ])
                ->one();
            
            if (! $record)
                continue;
            
            $CostbenefitItem = new CostbenefitItem();
            $CostbenefitItem->costbenefit_item_type_id = $record->id;
            $CostbenefitItem->costbenefit_calculation_id = $CostbenefitCalculation->id;
            $CostbenefitItem->value = $item['value'];
            
            if (! $CostbenefitItem->save())
                $modelsSaved[] = "CostbenefitItem[{$key}]";
        }
        
        if (empty($modelsSaved)) {
            $transaction->commit();
            $success = true;
        } else {
            $transaction->rollBack();
            $success = false;
        }
        
        return $success;
    }
}
