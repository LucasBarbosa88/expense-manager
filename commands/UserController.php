<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use Firebase\JWT\JWT;

class UserController extends Controller
{
  public $enableCsrfValidation = false; // para API REST

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => \app\components\JwtHttpBearerAuth::class,
    ];
    return $behaviors;
  }

  /**
   * Registro de usuário
   * POST /user/register
   */
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

    $token = $this->generateJwt($user->id);

    return [
      'message' => 'Usuário registrado com sucesso',
      'access_token' => $token
    ];
  }

  /**
   * Login de usuário
   * POST /user/login
   */
  public function actionLogin()
  {
    $params = Yii::$app->request->post();

    if (!isset($params['email'], $params['password'])) {
      return ['error' => 'Email e senha são obrigatórios'];
    }

    $user = User::findOne(['email' => $params['email']]);
    if (!$user || !Yii::$app->security->validatePassword($params['password'], $user->password_hash)) {
      return ['error' => 'Email ou senha inválidos'];
    }

    $token = $this->generateJwt($user->id);

    return ['access_token' => $token];
  }

  /**
   * Gera JWT para o usuário
   */
  private function generateJwt($userId)
  {
    $key = Yii::$app->params['jwtSecret'];
    $payload = [
      'iss' => 'http://localhost',
      'aud' => 'http://localhost',
      'iat' => time(),
      'exp' => time() + 3600, // expira em 1 hora
      'uid' => $userId,
    ];

    return JWT::encode($payload, $key, 'HS256');
  }
}
