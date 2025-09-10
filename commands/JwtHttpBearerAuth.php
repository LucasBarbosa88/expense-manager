<?php

namespace app\components;

use Yii;
use yii\filters\auth\AuthMethod;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHttpBearerAuth extends AuthMethod
{
  public function authenticate($user, $request, $response)
  {
    $authHeader = $request->getHeaders()->get('Authorization');
    if ($authHeader && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
      $token = $matches[1];
      try {
        $decoded = JWT::decode($token, new Key(Yii::$app->params['jwtSecret'], 'HS256'));
        return $user->loginById($decoded->uid);
      } catch (\Exception $e) {
        return null;
      }
    }
    return null;
  }
}
