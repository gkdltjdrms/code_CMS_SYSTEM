<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardListModel extends Model
{
    protected $table = 'board_list'; // 게시판 목록 테이블
    protected $primaryKey = 'id';
    protected $allowedFields = ['board_name', 'board_table', 'created_at'];
}
