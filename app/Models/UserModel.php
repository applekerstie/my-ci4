<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // 사용자 테이블 이름

    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'email', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    /*
    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) return $data;

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
    */


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
}