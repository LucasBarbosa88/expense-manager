<?php

namespace tests\functional;

use app\models\User;
use FunctionalTester;

class ExpenseCest
{
  private $expenseId;
  private $categoryId;

  public function _before(FunctionalTester $I)
  {
    $I->sendPOST('/category/create', [
      'name' => 'Categoria_' . uniqid()
    ]);
    $respCat = json_decode($I->grabResponse(), true);
    $this->categoryId = $respCat['id'] ?? null;
    if (!$this->categoryId) {
      throw new \RuntimeException('Falha ao criar categoria');
    }

    // Cria despesa
    $I->sendPOST('/expense/create', [
      'description' => 'Despesa Teste',
      'amount' => 100,
      'category_id' => 1,
      'user_id' => 1,
      'expense_date' => date('Y-m-d')
    ]);
    $resp = json_decode($I->grabResponse(), true);
    $this->expenseId = $resp['id'] ?? null;
    if (!$this->expenseId) {
      throw new \RuntimeException('Falha ao criar despesa');
    }
  }


  public function testUpdateExpense(FunctionalTester $I)
  {
    $I->sendPOST('/expense/update?id=' . $this->expenseId, [
      'description' => 'Despesa Atualizada',
      'amount' => 150
    ]);
    $I->seeResponseCodeIs(200);
    $I->seeResponseContains('"description":"Despesa Atualizada"');
    $I->seeResponseContains('"amount":150');
  }

  public function testDeleteExpense(FunctionalTester $I)
  {
    $I->sendPOST('/expense/delete?id=' . $this->expenseId);
    $I->seeResponseCodeIs(200);
    $I->seeResponseContains('"success":true');

    $I->sendGET('/expense/view?id=' . $this->expenseId);
    $I->seeResponseCodeIs(404);
  }

  public function testListExpenses(FunctionalTester $I)
  {
    $I->sendGET('/expense/index', ['pageSize' => 10]);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContains('"description":"Despesa Teste"');
  }

  public function testViewExpenseDetails(FunctionalTester $I)
  {
    $I->sendGET('/expense/view?id=' . $this->expenseId);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContains('"description":"Despesa Teste"');
    $I->seeResponseContains('"amount":100');
  }
}
