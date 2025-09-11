<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;

class UserController extends Controller
{
  public $enableCsrfValidation = false;

  public function actionRegister()
  {
    $params = Yii::$app->request->post();

    if (!isset($params['email'], $params['password'])) {
      return ['error' => 'Email e senha são obrigatórios'];
    }

    if (User::findOne(['email' => $params['email']])) {
      return ['error' => 'Email já cadastrado'];
    }

    $user = new User();
    $user->email = $params['email'];
    $user->password_hash = Yii::$app->security->generatePasswordHash($params['password']);
    $user->auth_key = Yii::$app->security->generateRandomString();

    if (!$user->save()) {
      return ['error' => $user->errors];
    }

    return [
      'message' => 'Usuário registrado com sucesso',
      'access_token' => $user->generateJwtToken()
    ];
  }

  public function actionLogin()
  {
    $params = Yii::$app->request->post();

    if (!isset($params['email'], $params['password'])) {
      return ['error' => 'Email e senha são obrigatórios'];
    }

    $user = User::findOne(['email' => $params['email']]);
    if (!$user || !$user->validatePassword($params['password'])) {
      return ['error' => 'Email ou senha inválidos'];
    }

    return [
      'access_token' => $user->generateJwtToken()
    ];
  }
}
