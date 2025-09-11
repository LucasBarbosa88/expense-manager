<?php

namespace app\controllers;

use app\components\JwtHttpBearerAuth;
use app\services\ExpenseService;
use Yii;
use yii\rest\Controller;

class ExpenseController extends Controller
{
  private $service;

  // public function behaviors()
  // {
  //   $behaviors = parent::behaviors();

  //   // JWT authentication
  //   $behaviors['authenticator'] = [
  //     'class' => JwtHttpBearerAuth::class,
  //   ];

  //   return $behaviors;
  // }

  public function init()
  {
    parent::init();
    $this->service = new ExpenseService();
  }

  public function actionCreate()
  {
    $data = Yii::$app->request->post();
    return $this->service->createExpense(Yii::$app->user->id, $data);
  }

  public function actionUpdate($id)
  {
    $data = Yii::$app->request->post();
    return $this->service->updateExpense($id, Yii::$app->user->id, $data);
  }

  public function actionDelete($id)
  {
    return $this->service->deleteExpense($id, Yii::$app->user->id);
  }

  public function actionIndex($page = 1, $limit = 10, $category_id = null, $start_date = null, $end_date = null)
  {
    $filters = [
      'category_id' => $category_id,
      'start_date'  => $start_date,
      'end_date'    => $end_date
    ];
    return $this->service->listExpenses(1, $filters, $page, $limit);
  }

  public function actionView($id)
  {
    $userId = Yii::$app->user->id;

    $expense = $this->service->getExpenseById($id, $userId);

    if (!$expense) {
      throw new \yii\web\NotFoundHttpException("Despesa não encontrada.");
    }

    return $expense;
  }
}
