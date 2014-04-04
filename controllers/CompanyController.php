<?php
namespace app\controllers;

use Yii;
use yii\base\Controller;
use app\models\Company;
use app\models\Industry;
use yii\web\Session;

class CompanyController extends Controller
{
	function init()
	{
		parent::init();
		if (isset($_GET['lang']))
		{
			\Yii::$app->language = $_GET['lang'];
			\Yii::$app->session['lang'] = \Yii::$app->language;
		}
		else if ( isset(\Yii::$app->session['lang']) )
		{
			\Yii::$app->session['lang'] = \Yii::$app->language;
		}
	}
	
	public function actionIndex()
	{
		$id = $_GET['id'];
		$company = Company::find($id);
	
		return $this->render('view', ['company'=>$company]);
	}
}

?>