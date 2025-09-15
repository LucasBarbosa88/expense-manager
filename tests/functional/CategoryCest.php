<?php

namespace tests\functional;

use app\models\User;
use FunctionalTester;

class CategoryCest
{
  private $token;
  private $categoryId;

  public function _before(FunctionalTester $I)
  {
    // $I->sendPOST('/user/login', ['email' => 'teste@teste.com', 'password' => '123456']);
    // $response = json_decode($I->grabResponse(), true);
    // $this->token = $response['token'];

    //$I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    $I->sendPOST('/category/create', ['name' => 'Transporte_' . uniqid()]);
    $resp = json_decode($I->grabResponse(), true);
    $this->categoryId = $resp['id'];
  }

  public function testUpdateCategory(FunctionalTester $I)
  {
    //$I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    $newName = 'Transporte Atualizado_' . uniqid();
    $I->sendPOST('/category/update?id=' . $this->categoryId, ['name' => $newName]);
    $I->seeResponseCodeIs(200);
    $I->seeResponseContains('"name":"' . $newName . '"');
  }

  public function testDeleteCategory(FunctionalTester $I)
  {
    //$I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    $id = $I->haveInDatabase('category', [
      'name' => 'Transporte_' . uniqid(),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);
    $I->sendPOST('/category/delete?id=' . $id);
    $I->seeResponseCodeIs(200);
    $I->seeResponseContains('"success":true');

    $I->sendGET('/category/view?id=' . $id);
    $I->seeResponseCodeIs(404);
  }

  public function testListCategories(FunctionalTester $I)
  {
    //$I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    $I->haveInDatabase('category', [
      'name' => 'Transporte_' . uniqid(),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);

    $I->sendGet('/category/index', ['pageSize' => 10]);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContains('"name":"Transporte"');
  }

  public function testViewCategoryDetails(FunctionalTester $I)
  {
    //$I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    $id = $I->haveInDatabase('category', [
      'name' => 'Transporte_' . uniqid(),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);

    $I->sendGET('/category/view?id=' . $id);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContains('"name":"Lazer"');
  }
}
