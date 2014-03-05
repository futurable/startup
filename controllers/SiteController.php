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

class SiteController extends Controller
{
	private $tokenKey;
	
	public function behaviors()
	{
		return [
		];
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	public function actionIndex()
	{
		$tokenKey = new TokenKey();
		$tokenKey->load($_POST);
		$action = 'index';
		
		# Token key has been sent
		if($tokenKey->token_key){
			$record = $this->getReclaimedTokenKey($tokenKey);
			
			# Check if the token key is valid
			if($this->validateTokenKey($tokenKey->token_key) === true){
				$models = $this->getCreateCompanyModels();
				
				if($models['company']['attributes']['name']){
					$action = 'save';
				} else {
					$action = 'create';
				}
				
			} elseif($record == true) {
				# Token key is already used
				$tokenKey->addError('token_key', Yii::t('TokenKey', 'Token key is already used on {dateTime}.', ['dateTime'=>$record->reclaim_date] ));
			
			}
			else{
				# Invalid token key
				$tokenKey->addError('token_key', Yii::t('TokenKey', 'Invalid token key.'));
			
			}
		}
		
		# Token key is valid
		if($action == 'create'){
			return $this->render('create', $models);
		}
		
		# Company has been sent
		if($action == 'save'){
			if($this->saveModels() === true){
				return $this->render('view', $models);
			}
			else {
				$tokenKey->addError('token_key', Yii::t("TokenKey", "Error while saving the company. Please report this incident to helpdesk@futurality.fi"));
				$action = 'index';
			}
		}
		# Token key has not been sent
		if($action == 'index'){
			return $this->render('index', ['tokenKey' => $tokenKey]);
		}
	}
	
	private function validateTokenKey($tokenKey){
		$isValid = false;
		
		$record = TokenKey::find()
			->select(['token_key', 'reclaim_date'])
			->where(['and', 'token_key=:token_key', 'reclaim_date IS NULL' ])
			->addParams([':token_key' => $tokenKey])
			->one();
		
		if(!$record) $isValid = false;
		else $isValid = true;
		
		return $isValid;
	}
	
	private function getReclaimedTokenKey($tokenKey){
		$record = TokenKey::find()
		->select(['reclaim_date'])
		->where(['and', 'token_key=:token_key'])
		->addParams([':token_key' => $tokenKey->token_key])
		->one();
	
		return $record;
	}
	
	private function getCreateCompanyModels(){
		$tokenKey = new TokenKey();
		$tokenKey->load($_POST);
		
		$company = new Company();
		$company->load($_POST);
		
		$industry = new Industry();
		$industry->load($_POST);
		
		$costBenefitCalculation = new CostbenefitCalculation();
		$costBenefitItemTypes = CostbenefitItemType::find()->orderBy('order')->all();
		
		$industryArray = $this->getIndustryArray();
		$industrySetupArray = $this->getIndustrySetupArray();
		$employeeArray = $this->getEmployeeArray();
		
		$render = [
			'tokenKey' => $tokenKey,
			'company'=>$company,
			'industry'=>$industry,
			'industryArray'=>$industryArray,
			'industrySetupArray'=>$industrySetupArray,
			'employeeArray'=>$employeeArray,
			'costBenefitCalculation'=>$costBenefitCalculation,
			'costBenefitItemTypes'=>$costBenefitItemTypes,
		];
		
		return $render;
	}
	
	private function getIndustryArray(){
		# TODO: Tie this to the token key
		$record = Industry::find()->all();
		
		foreach($record as $row){
			$industryDropdown[$row->id] = Yii::t('Industry', $row->name);
		}
		
		return $industryDropdown;
	}
	
	private function getEmployeeArray(){
		# TODO: Tie these to the industry
		$keys = range(10,100,10);
		$values = range(10,100,10);
		foreach($values as $key => $value){
			$values[$key] = $value."+";
		}
		$array2 = array_combine( $keys, $values );
		
		$employeeDropdown = array_combine( range(1,9), range(1,9) ) + $array2;
		
		return $employeeDropdown;
	}
	
	private function getIndustrySetupArray(){
		// Get industry setups
		$industrySetups = IndustrySetup::find()->all();
		foreach($industrySetups as $industrySetup){
			$industrySetupArray[$industrySetup->industry_id] = array(
				'turnover' => $industrySetup->turnover,
				'minWage' => $industrySetup->minimum_wage_rate,
				'avgWage' => $industrySetup->average_wage_rate,
				'maxWage' => $industrySetup->maximum_wage_rate,
				'rents' => $industrySetup->rents,
				'communication' => $industrySetup->communication,
			);
		}
		return $industrySetupArray;
	}
	
	private function saveModels(){
		$success = false;
		
		return $success;
	}
}
