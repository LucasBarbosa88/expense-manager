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

  public function actionIndex($page = 1, $limit = 10, $id = null, $name = null)
  {
    $filters = [
      'id' => $id,
      'name'  => $name,
    ];
    return $this->service->listCategories(Yii::$app->user->id, $filters, $page, $limit);
  }
}
