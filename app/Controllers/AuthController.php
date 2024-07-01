<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\UserModel;

class AuthController extends Controller
{
    // 로그인 페이지 표시
    public function loginForm()
    {
        return view('auth/login');
    }

    // 로그인 처리
    public function login()
    {
        // POST 데이터에서 사용자 이름과 비밀번호 가져오기
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 사용자 모델 로드
        //$userModel = new \App\Models\UserModel();
        $userModel = new UserModel();
        
        // 사용자 로그인 확인
        $user = $userModel->login($username, $password);


        if ($user) {

            // 세션에 사용자 정보 저장 (로그인 세션 유지)
            $session = session();
            $session->set('user_id', $user['id']);
            $session->set('username', $user['username']);

            // 로그인 성공 후 리다이렉트
            return redirect()->to('/posts');
        } else {
            // 로그인 실패 처리 (예: 다시 로그인 폼으로 리다이렉트)
            return redirect()->back()->with('error', '로그인 실패: 사용자 이름 또는 비밀번호가 잘못되었습니다.');
        }


        /*
        // 240623 kerstie
        if ($user) {
            $token = $userModel->createJWT($user);
            
            // 240625 kerstie
            setcookie('jwt', $token, time() + 3600, '/', '', true, true); // HTTP Only 쿠키 설정
            return redirect()->to('/posts');

        } else {

            // 240625 kerstie
            return redirect()->back()->with('error', '로그인 실패: 사용자 이름 또는 비밀번호가 잘못되었습니다.');

        }
        */
    }

    /*
    public function profile()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if ($authHeader) {
            $token = $authHeader->getValue();
            $userModel = new UserModel();
            $userData = $userModel->verifyJWT($token);

            if ($userData) {
                return $this->response->setJSON($userData);
            } else {
                return $this->response->setJSON(['error' => '인증 실패'], 401);
            }
        } else {
            return $this->response->setJSON(['error' => '토큰이 제공되지 않았습니다.'], 401);
        }
    }
    */

    // 로그아웃 처리
    public function logout()
    {
        
        // 세션을 파기하여 로그아웃
        $session = session();
        $session->destroy();

        // 로그아웃 후 리다이렉트
        return redirect()->to('/');
        
        /*
        // 240625 kerstie
        setcookie('jwt', '', time() - 3600, '/', '', true, true);
        return redirect()->to('/login');
        */
    }
}