<?php

namespace tests\functional;

use app\models\User;
use FunctionalTester;


class AuthCest
{
  private $token;

  public function _before(FunctionalTester $I)
  {
  }

  public function registerUser(FunctionalTester $I)
  {
    $I->sendPOST('/user/register', [
      'email' => 'testuser@example.com',
      'password' => '12345678'
    ]);
    $I->seeResponseCodeIs(201);
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson(['email' => 'testuser@example.com']);
  }

  public function loginUser(FunctionalTester $I)
  {
    $I->sendPOST('/user/login', [
      'email' => 'testuser@example.com',
      'password' => '12345678'
    ]);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();

    $response = json_decode($I->grabResponse(), true);
    $I->assertArrayHasKey('token', $response);

    $this->token = $response['token'];

    $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    $I->sendGET('/expenses');
    $I->seeResponseCodeIs(200);
  }

  public function loginUserInvalid(FunctionalTester $I)
  {
    $I->sendPOST('/user/login', [
      'email' => 'kk@kk.com',
      'password' => 'kk∂'
    ]);
    $I->seeResponseCodeIs(401);
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson(['error' => 'Credenciais inválidas']);
  }

  public function accessProtectedWithoutToken(FunctionalTester $I)
  {
    $I->sendGET('/expenses');
    $I->seeResponseCodeIs(401);
    $I->seeResponseContainsJson(['error' => 'Credenciais inválidas.']);
  }
}
