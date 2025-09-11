<?php

namespace app\services;

use app\models\Expense;
use Yii;
use yii\web\NotFoundHttpException;

class ExpenseService
{
  public function createExpense($userId, $data)
  {
    $expense = new Expense($data);
    $expense->user_id = $userId;

    if (!$expense->save()) {
      throw new \Exception(json_encode($expense->errors));
    }

    return $expense;
  }

  public function updateExpense($id, $userId, $data)
  {
    $expense = Expense::findOne(['id' => $id, 'user_id' => $userId]);
    if (!$expense) throw new NotFoundHttpException('Despesa não encontrada');

    $expense->load($data, '');
    if (!$expense->save()) {
      throw new \Exception(json_encode($expense->errors));
    }

    return $expense;
  }

  public function deleteExpense($id, $userId)
  {
    $expense = Expense::findOne(['id' => $id, 'user_id' => $userId]);
    if (!$expense) throw new NotFoundHttpException('Despesa não encontrada');

    return $expense->delete();
  }

  public function listExpenses($userId, $filters = [], $page = 1, $limit = 10)
  {
    $query = Expense::find()->where(['user_id' => $userId]);

    if (isset($filters['category_id'])) {
      $query->andWhere(['category_id' => $filters['category_id']]);
    }
    if (isset($filters['start_date'])) {
      $query->andWhere(['>=', 'expense_date', $filters['start_date']]);
    }
    if (isset($filters['end_date'])) {
      $query->andWhere(['<=', 'expense_date', $filters['end_date']]);
    }

    $query->orderBy(['expense_date' => SORT_DESC]);
    $pagination = new \yii\data\Pagination([
      'totalCount' => $query->count(),
      'page' => $page - 1,
      'pageSize' => $limit
    ]);

    return [
      'data' => $query->offset($pagination->offset)->limit($pagination->limit)->all(),
      'pagination' => [
        'total' => $pagination->totalCount,
        'page' => $page
      ]
    ];
  }

  public function getExpenseById($id, $userId)
  {
    return \app\models\Expense::find()
      ->where(['id' => $id, 'user_id' => $userId])
      ->one();
  }
}
