<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return view('/user/register');
    }

    public function create()
    {
        $model = new \App\Models\UserModel();

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'email'    => $this->request->getPost('email'),
        ];

        $model->insert($data);

        return redirect()->to('/login'); // 가입 후 로그인 페이지로 리다이렉트
    }
}