<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    /**
     * 회원가입 폼 페이지
     */
    public function signup()
    {
        echo view('templates/header', ['title' => '회원가입']);
        echo view('auth/signup');
        echo view('templates/footer');
    }

    /**
     * 회원가입 처리
     */
    public function processSignup()
    {
        // 폼 데이터 가져오기
        $username = $this->request->getPost('username');
        $user_id = $this->request->getPost('user_id');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // 비밀번호 확인
        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', '비밀번호가 일치하지 않습니다.');
        }

        // 유저 모델 사용
        $userModel = new UserModel();
        $data = [
            'username' => $username,
            'user_id' => $user_id,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT), // 비밀번호 암호화
        ];

        // 데이터베이스에 삽입
        if ($userModel->insert($data)) {
            return redirect()->to('/auth/login')->with('message', '회원가입 성공!');
        } else {
            return redirect()->back()->with('error', '회원가입 실패');
        }
    }

    /**
     * 로그인 폼 페이지
     */
    public function login()
    {
        echo view('templates/header', ['title' => '로그인']);
        echo view('auth/login');
        echo view('templates/footer');
    }

    /**
     * 로그인 처리
     */
    public function processLogin()
    {
        // 폼 데이터 가져오기
        $user_id = $this->request->getPost('user_id'); //해당 고유값 아이디
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password'); // 사용자가 입력한 비밀번호

        // 유저 모델 사용
        $userModel = new UserModel();
        $user = $userModel->where('user_id', $username)->first(); // 데이터베이스에서 사용자 조회

        // 사용자와 비밀번호 확인
        if ($user && password_verify($password, $user['password'])) {
            // 사용자 역할에 따라 처리
            if ($user['role'] === 'admin') {
                // 관리자 로그인
                session()->set([
                    'isLoggedIn' => true,
                    'userId' => $user['id'],
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'role' => 'admin'
                ]);
                return redirect()->to('/admin'); // 관리자 대시보드로 리다이렉트
            } else {
                // 일반 사용자 로그인
                session()->set([
                    'isLoggedIn' => true,
                    'userId' => $user['id'],
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'role' => 'user'
                ]);
                return redirect()->to('/'); // 사용자 홈으로 리다이렉트
            }
        } else {
            // 로그인 실패 시 에러 메시지
            return redirect()->back()->with('error', '아이디 또는 비밀번호가 잘못되었습니다.');
        }
    }

    /**
     * 로그아웃 처리
     */
    public function logout()
    {
        session()->destroy(); // 세션 파기
        return redirect()->to('/');
    }
}
