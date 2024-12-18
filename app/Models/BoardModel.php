<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardModel extends Model
{
    protected $table = 'boards';       // 테이블 이름
    protected $primaryKey = 'id';      // 기본 키
    protected $allowedFields = ['name', 'created_at', 'user_id'];

    protected $useTimestamps = true;   // created_at, updated_at 자동 생성
}
