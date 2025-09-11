<?php

namespace app\components;

use Yii;
use yii\filters\auth\AuthMethod;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\models\User;

class JwtHttpBearerAuth extends AuthMethod
{
  public function authenticate($user, $request, $response)
  {
    $authHeader = $request->getHeaders()->get('Authorization');
    if ($authHeader && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
      preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
      $token = $matches[1];
      Yii::info("Token recebido: " . $token, __METHOD__);

      try {
        $decoded = JWT::decode($token, new Key(Yii::$app->params['jwtSecret'], 'HS256'));
        $identity = User::findIdentity($decoded->uid);
        if ($identity) {
          return $identity;
        }
      } catch (\Exception $e) {
        return null;
      }
    }
    return null;
  }
}
