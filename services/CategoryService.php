<?php

namespace app\services;

use app\models\Category;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class CategoryService
{
  public function createCategory($data)
  {
    $category = new Category($data);

    if (!$category->save()) {
      throw new \Exception(json_encode($category->errors));
    }

    return $category;
  }

  public function updateCategory($id, $data)
  {
    $category = Category::findOne(['id' => $id]);
    if (!$category) throw new NotFoundHttpException('Categoria não encontrada');

    $category->load($data, '');
    if (!$category->save()) {
      throw new \Exception(json_encode($category->errors));
    }

    return $category;
  }

  public function deleteCategory($id)
  {
    $category = Category::findOne(['id' => $id]);
    if (!$category) throw new NotFoundHttpException('Categoria não encontrada');
    if ($category && $category->delete()) {
      return ['success' => true];
    }
    return ['success' => false];
  }

  public function listCategories($filters = [], $page = 1, $limit = 10)
  {
    $query = Category::find();

    if (isset($filters['id'])) {
      $query->andWhere(['id' => $filters['id']]);
    }

    if (isset($filters['name'])) {
      $query->andWhere(['like', 'name', $filters['name']]);
    }

    $query->orderBy(['name' => SORT_ASC]);

    // força page e limit como inteiros válidos
    $page = is_array($page) ? 1 : (int)$page;
    $page = max($page, 1);

    $limit = is_array($limit) ? 10 : (int)$limit;
    $limit = max($limit, 1);

    $pagination = new Pagination([
      'totalCount' => $query->count(),
      'page' => $page - 1,
      'pageSize' => $limit,
    ]);

    $categories = $query->offset($pagination->offset)
      ->limit($pagination->limit)
      ->all();

    return [
      'data' => $categories,
      'pagination' => [
        'total' => $pagination->totalCount,
        'page' => $page,
        'pageSize' => $limit,
        'totalPages' => ceil($pagination->totalCount / $limit),
      ],
    ];
  }
}
