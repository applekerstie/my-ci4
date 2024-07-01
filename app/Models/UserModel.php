<?php

namespace App\Models;

use CodeIgniter\Model;

/*
// 240623 kerstie
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
*/

class UserModel extends Model
{
    protected $table = 'users'; // 사용자 테이블 이름

    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'email', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 사용자 로그인을 위한 메서드
    public function login($username, $password)
    {
        /*
        $user = $this->where('username', $username)
                     ->where('password', $password)
                     ->first();           
        return $user;
        */

        // 사용자 이름으로 사용자 조회
        $user = $this->where('username', $username)->first();


        if ($user) {
          // 데이터베이스에서 가져온 해시된 비밀번호와 입력된 비밀번호를 비교
          if (password_verify($password, substr( $user['password'], 0, 60 ))) {
            return $user;
          }
        }

        return null;
    }

    /*
    // 240623 kerstie
    // JWT 생성 메서드
    public function createJWT($user)
    {
        $config = config('JWT');

        $payload = [
            'iss' => $config->issuer,
            'aud' => $config->audience,
            'iat' => time(),
            'exp' => time() + $config->expiration,
            'data' => [
                'id' => $user['id'],
                'username' => $user['username'],
            ]
        ];

        return JWT::encode($payload, $config->key, 'HS256');
    }

    // 240623 kerstie
    // JWT 검증 메서드
    public function verifyJWT()
    {   
        if( isset($_COOKIE['jwt']) ){
          $token = $_COOKIE['jwt'];

          $config = config('JWT');
          try {
              $decoded = JWT::decode($token, new Key($config->key, 'HS256'));
              return (array) $decoded->data;
          } catch (\Exception $e) {
              return null;
          }
        }

        return null;
    }
    */

}