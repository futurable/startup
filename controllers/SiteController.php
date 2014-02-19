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

		if($tokenKey->token_key) {
			$company = new Company();
			$industry = new Industry();
			
            $costBenefitCalculation = new CostbenefitCalculation();
            
            $costBenefitItemTypes = CostbenefitItemType::find()->all();
            
			return $this->render('create', [
				'tokenKey' => $tokenKey,
				'company'=>$company,
				'industry'=>$industry,
				'costBenefitCalculation'=>$costBenefitCalculation,
				'costBenefitItemTypes'=>$costBenefitItemTypes,
			]);
			
		} else {
			return $this->render('index', array('tokenKey' => $tokenKey));
		}
	}
	
}
