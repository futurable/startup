<?php
namespace app\controllers;

use Yii;
use yii\base\Controller;
use app\models\Company;
use app\models\Industry;

class CompanyController extends Controller
{
	public function actionIndex()
	{
		$id = $_GET['id'];
		$company = Company::find($id);
	
		return $this->render('view', ['company'=>$company]);
	}
}

?>