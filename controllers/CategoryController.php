<?php

namespace app\controllers;

use app\services\CategoryService;
use Yii;
use yii\rest\Controller;

class CategoryController extends Controller
{
  private $service;

  public function init()
  {
    parent::init();
    $this->service = new CategoryService();
  }

  public function actionCreate()
  {
    $data = Yii::$app->request->post();
    return $this->service->createCategory($data);
  }

  public function actionUpdate($id)
  {
    $data = Yii::$app->request->post();
    return $this->service->updateCategory($id,$data);
  }

  public function actionDelete($id)
  {
    return $this->service->deleteCategory($id);
  }

  public function actionIndex($page = 1, $limit = 10, $category_id = null, $start_date = null, $end_date = null)
  {
    $filters = [
      'category_id' => $category_id,
      'start_date'  => $start_date,
      'end_date'    => $end_date
    ];
    return $this->service->listExpenses(Yii::$app->user->id, $filters, $page, $limit);
  }
}
