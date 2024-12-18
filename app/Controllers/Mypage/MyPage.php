<?php

namespace App\Controllers\MyPage;

use App\Controllers\BaseController;

class MyPage extends BaseController
{
    public function index()
    {
        // 마이페이지 메인 화면
        return view('mypage/index');
    }

    public function posts()
    {
        // 사용자가 로그인하지 않은 경우 리다이렉트
        if (!session()->has('username')) {
            return redirect()->to('/login')->with('error', '로그인이 필요합니다.');
        }

        $userId = session()->get('user_id'); // 현재 로그인한 사용자 ID

        // 작성 글 조회
        $builder = $this->db->table('board_test'); // 모든 게시글이 저장된 테이블
        $posts = $builder->select('id, subject, created_at')
            ->where('user_id', $userId) // 현재 사용자 ID로 필터링
            ->orderBy('created_at', 'DESC') // 최신 글 순서
            ->get()
            ->getResultArray();

        return view('mypage/posts', [
            'posts' => $posts,
        ]);
    }
}
