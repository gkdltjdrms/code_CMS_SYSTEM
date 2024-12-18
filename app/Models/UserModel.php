<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // 테이블 이름
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'user_id'];
    protected $useTimestamps = true; // created_at, updated_at 자동 관리


    // 데이터 검증 규칙
    protected $validationRules = [
        'user_id' => 'required|is_unique[users.user_id]', // user_id 중복 방지
        'email' => 'required|valid_email',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'is_unique' => '이미 사용 중인 아이디입니다.',
        ],
        'email' => [
            'valid_email' => '유효한 이메일 주소를 입력하세요.',
        ],
        'password' => [
            'min_length' => '비밀번호는 최소 8자 이상이어야 합니다.',
        ],
    ];
}
