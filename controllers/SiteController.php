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
		$this->tokenKey = new TokenKey();
		$this->tokenKey->load($_POST);

		$tokenKey = $this->tokenKey;
		# Token key has been sent
		if($tokenKey->token_key){
			$record = $this->getReclaimedTokenKey($tokenKey);
			
			# Check if the token key is valid
			if($this->validateTokenKey($tokenKey->token_key) === true){
				$models = $this->getCreateCompanyModels();
				return $this->render('create', $models);
			} elseif($record == true) {
				# Token key is already used
				$tokenKey->addError('token_key', Yii::t('TokenKey', 'Token key is already used on {dateTime}.', ['dateTime'=>$record->reclaim_date] ));
			}
			else{
				# Invalid token key
				$tokenKey->addError('token_key', Yii::t('TokenKey', 'Invalid token key.'));
			}
		}
		
		# Token key has not been sent
		return $this->render('index', array('tokenKey' => $tokenKey));
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
		$tokenKey = $this->tokenKey;
		$company = new Company();
		$industry = new Industry();
		
		$costBenefitCalculation = new CostbenefitCalculation();
		
		$costBenefitItemTypes = CostbenefitItemType::find()->all();
		
		$industryArray = $this->getIndustryArray();
		$employeeArray = $this->getEmployeeArray();
		
		$render = [
			'tokenKey' => $tokenKey,
			'company'=>$company,
			'industry'=>$industry,
			'industryArray'=>$industryArray,
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
			$industryDropdown[$row->id] = $row->description;
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
}
