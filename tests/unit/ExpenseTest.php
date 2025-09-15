<?php

namespace tests\unit;

use app\models\Expense;
use Codeception\Test\Unit;

class ExpenseTest extends Unit
{
    public function testValidation()
    {
        $expense = new Expense();
        $expense->description = '';
        $expense->amount = null;
        $expense->category_id = null;
        $expense->expense_date = null;

        $this->assertFalse($expense->validate(['description', 'amount', 'category_id', 'expense_date']));

        $expense->description = 'Teste';
        $expense->amount = 120;
        $expense->category_id = 1;
        $expense->expense_date = date('Y-m-d');

        $this->assertTrue($expense->validate());
    }
}
