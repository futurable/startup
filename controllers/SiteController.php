<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Company;
use app\models\TokenKey;

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
			return $this->render('create', array('tokenKey' => $tokenKey, 'company' => $company));
		} else {
			return $this->render('index', array('tokenKey' => $tokenKey));
		}
	}
	
}
