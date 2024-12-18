<?php

namespace App\Controllers\Register;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function signupForm()
    {
        // 회원가입 페이지 호출
        echo view('register/templates/header');
        echo view('register/signup');
        echo view('register/templates/footer');
    }

    public function processSignup()
    {
        $userModel = new UserModel();

        // 요청 데이터 가져오기
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        // 데이터 저장 시도
        if ($userModel->insert($data)) {
            return redirect()->to('/register/success')->with('message', '회원가입이 완료되었습니다.');
        } else {
            return redirect()->back()->with('error', '회원가입 중 문제가 발생했습니다.');
        }
    }

    public function success()
    {
        // 회원가입 성공 페이지
        echo view('register/templates/header');
        echo "<div class='container'><h2>회원가입이 완료되었습니다!</h2><p>로그인 후 이용해주세요.</p></div>";
        echo view('register/templates/footer');
    }
}
