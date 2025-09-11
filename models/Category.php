<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
  public static function tableName()
  {
    return 'category'; // nome da tabela no MySQL
  }

  public function rules()
  {
    return [
      ['name', 'required'],
      ['name', 'string', 'max' => 50],
      ['name', 'unique'],
    ];
  }

  public function getExpenses()
  {
    return $this->hasMany(Expense::class, ['category_id' => 'id']);
  }
}