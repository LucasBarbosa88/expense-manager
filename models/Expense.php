<?php

namespace app\models;

class Expense extends \yii\db\ActiveRecord
{
  public static function tableName()
  {
    return 'expense';
  }

  public function rules()
  {
    return [
      [['description', 'amount', 'expense_date', 'category_id'], 'required'],
      ['amount', 'number'],
      ['expense_date', 'date', 'format' => 'php:Y-m-d'],
      ['category_id', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
    ];
  }

  public function getCategory()
  {
    return $this->hasOne(Category::class, ['id' => 'category_id']);
  }
}
