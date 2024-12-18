<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Board extends BaseController
{
    // 게시판 목록 보기
    public function index()
    {
        $boardListModel = model('BoardListModel');
        $data['boards'] = $boardListModel->findAll(); // board_list 테이블에서 모든 데이터 가져오기

        return view('admin/board_list', $data);
    }


    // 게시판 생성 폼 페이지
    public function create()
    {
        $data['title'] = '게시판 생성';
        return view('admin/board_create', $data);
    }

    // 게시판 생성 처리
    public function store()
    {
        // 입력 데이터 가져오기
        $boardName = $this->request->getPost('board_name');
        $boardTable = $this->request->getPost('board_table');

        // 입력값 유효성 검사
        if (empty($boardName) || empty($boardTable)) {
            return redirect()->back()->with('error', '게시판 이름과 테이블명을 모두 입력해주세요.');
        }

        // 테이블명에 접두사 추가
        $boardTable = 'board_' . strtolower(preg_replace('/\s+/', '_', $boardTable)); // 공백 제거, 소문자로 변환

        // 데이터베이스 연결
        $db = \Config\Database::connect();

        // 테이블명 유효성 검사
        if ($db->tableExists($boardTable)) {
            return redirect()->back()->with('error', "테이블명이 중복됩니다. ('$boardTable')");
        }

        // board_list 테이블에 데이터 저장
        $boardModel = model('BoardListModel');
        $boardModel->insert([
            'board_name' => $boardName,
            'board_table' => $boardTable,
        ]);

        // 새 게시판 테이블 생성
        $forge = \Config\Database::forge();
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->createTable($boardTable);

        return redirect()->to('/admin/boards')->with('message', "새 게시판 '$boardName' 이(가) 성공적으로 생성되었습니다.");
    }


    public function delete($id)
    {
        $boardModel = model('BoardListModel');
        $db = \Config\Database::connect();

        // 게시판 정보 가져오기
        $board = $boardModel->find($id);

        if (!$board) {
            return redirect()->back()->with('error', '존재하지 않는 게시판입니다.');
        }

        // 테이블명에 접두사 추가 (board_ 붙이기)
        $tableName = $board['board_table'];

        // 테이블 삭제
        $forge = \Config\Database::forge();
        try {
            if ($db->tableExists($tableName)) {
                $forge->dropTable($tableName);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '테이블 삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }

        // board_list에서 해당 게시판 정보 삭제
        $boardModel->delete($id);

        return redirect()->to('/admin/boards')->with('message', '게시판이 성공적으로 삭제되었습니다.');
    }





}
