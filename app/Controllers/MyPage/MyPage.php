<?php

namespace App\Controllers\MyPage;

use App\Controllers\BaseController;
use Config\Database; // Database 서비스 호출

class MyPage extends BaseController
{
    public function index()
    {
        // 마이페이지 메인 화면
        return view('mypage/index');
    }

    //마이페이지 자신이 작성한 글 모음
    public function posts()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', '로그인이 필요합니다.');
        }

        $userId = session()->get('user_id'); // 현재 로그인한 사용자 ID
        $db = Database::connect();

        // 검색 조건 가져오기
        $searchType = $this->request->getVar('searchType'); // 검색 유형 (제목 또는 내용)
        $searchKeyword = $this->request->getVar('search'); // 검색어
        $perPage = 5; // 한 페이지에 표시할 게시글 수
        $currentPage = $this->request->getVar('page') ?? 1; // 현재 페이지 번호

        // board_list에서 게시판 정보 가져오기
        $boardTables = $db->table('board_list')
            ->select('board_table, board_name')
            ->get()
            ->getResultArray();

        $posts = []; // 사용자 작성 글 저장
        $totalPosts = 0; // 총 게시글 수

        foreach ($boardTables as $table) {
            $tableName = $table['board_table']; // 게시판 테이블 이름
            $boardName = $table['board_name']; // 게시판 이름

            $builder = $db->table($tableName);

            // 검색 조건 추가
            if (!empty($searchKeyword) && !empty($searchType)) {
                if ($searchType === 'subject') {
                    $builder->like('subject', $searchKeyword);
                } elseif ($searchType === 'content') {
                    $builder->like('content', $searchKeyword);
                }
            }

            // 작성자 필터링
            $builder->where('user_id', $userId);

            // 총 게시글 수 가져오기
            $countBuilder = clone $builder; // 카운트용 빌더 클론
            $totalPosts += $countBuilder->countAllResults(false);

            // 페이징 처리
            $userPosts = $builder->select('id, subject, created_at')
                ->orderBy('created_at', 'DESC')
                ->limit($perPage, ($currentPage - 1) * $perPage)
                ->get()
                ->getResultArray();

            foreach ($userPosts as $post) {
                $post['table_name'] = $tableName;
                $post['board_name'] = $boardName;
                $posts[] = $post;
            }
        }

        // 페이징 객체 생성
        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($currentPage, $perPage, $totalPosts, 'custom_korean');

        return view('mypage/posts', [
            'posts' => $posts,
            'pagerLinks' => $pagerLinks,
            'searchType' => $searchType,
            'searchKeyword' => $searchKeyword,
        ]);
    }


    public function deletePost()
    {
        // 사용자 인증 확인
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', '로그인이 필요합니다.');
        }

        $userId = session()->get('user_id'); // 현재 로그인한 사용자 ID
        $tableName = $this->request->getPost('table_name'); // 삭제할 게시판 테이블
        $postId = $this->request->getPost('id'); // 삭제할 게시글 ID

        // 데이터베이스 연결
        $db = Database::connect();

        // 게시글 확인
        $builder = $db->table($tableName);
        $post = $builder->where('id', $postId)->where('user_id', $userId)->get()->getRowArray(); // 수정: getRowArray()

        if (!$post) {
            return redirect()->back()->with('error', '게시글을 찾을 수 없거나 삭제 권한이 없습니다.');
        }

        // 파일 및 이미지 삭제
        $this->deleteFile($post['file_path']);
        $this->deleteFile($post['image_path']);

        // 게시글 삭제
        $builder->where('id', $postId)->delete();

        return redirect()->back()->with('message', '게시글이 삭제되었습니다.');
    }





    // 파일 삭제 처리
    private function deleteFile($filePath)
    {
        if (!empty($filePath) && is_file($filePath)) { // 파일인지 확인
            unlink($filePath); // 파일 삭제
        }
    }


}
