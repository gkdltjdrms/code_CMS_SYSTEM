<?php

namespace App\Controllers\Board;

use CodeIgniter\Controller;
use App\Models\BoardListModel;

class BoardController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); // 데이터베이스 연결
    }

    // 게시판 목록 보기
    public function boardList()
    {
        $boardModel = new BoardListModel();
        $data = [
            'boards' => $boardModel->findAll(),
            'title' => '게시판 목록'
        ];

        return view('board/board_list', $data);
    }

    // 게시판 글 목록
    public function index($tableName)
    {
        if (!$this->db->tableExists($tableName)) {
            return redirect()->to('/')->with('error', '존재하지 않는 게시판입니다.');
        }

        // 게시판 이름 가져오기
        $boardInfo = $this->db->table('board_list')
            ->select('board_name')
            ->where('board_table', $tableName)
            ->get()
            ->getRowArray();

        if (!$boardInfo) {
            return redirect()->to('/')->with('error', '유효하지 않은 게시판입니다.');
        }
        $boardName = $boardInfo['board_name']; // 해당 게시판 이름


        $perPage = 5; // 페이지당 표시할 게시글 수
        $page = $this->request->getVar('page') ?? 1; // 현재 페이지 번호
        $searchType = $this->request->getVar('searchType'); // 검색 유형
        $searchKeyword = $this->request->getVar('search'); // 검색어

        $builder = $this->db->table($tableName);
        $builder->orderBy('created_at', 'DESC');

        // 검색 조건 추가
        if (!empty($searchKeyword) && !empty($searchType)) {
            if ($searchType === 'subject') {
                $builder->like('subject', $searchKeyword); // 제목에서 검색
            } elseif ($searchType === 'content') {
                $builder->like('content', $searchKeyword); // 내용에서 검색
            }
        }

        // 총 게시글 개수
        $totalPosts = $builder->countAllResults(false);

        // 게시글 가져오기
        $posts = $builder
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        // Pager 생성
        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($page, $perPage, $totalPosts, 'custom_korean'); // 한글 템플릿 사용

        return view('board/list', [
            'posts' => $posts,
            'pagerLinks' => $pagerLinks,
            'tableName' => $tableName,
            'boardName' => $boardName,
            'searchKeyword' => $searchKeyword, // 검색어 전달
            'searchType' => $searchType, // 검색 유형 전달
        ]);
    }








    // 게시글 보기
    public function view($tableName, $id)
    {
        $post = $this->db->table($tableName)->where('id', $id)->get()->getRowArray();

        if (!$post) {
            return redirect()->back()->with('error', '존재하지 않는 게시글입니다.');
        }

        // 게시판 이름 추가
        $post['table_name'] = $tableName;

        return view('board/view', ['post' => $post, 'tableName' => $tableName]);
    }

    // 글 작성 페이지
    public function create($tableName)
    {
        return view('board/create', ['tableName' => $tableName]);
    }

    // 글 저장
    public function store($tableName)
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', '로그인이 필요합니다.');
        }

        // 폼 데이터 수집
        $data = [
            'user_id' => $userId,
            'subject' => $this->request->getPost('subject'),
            'content' => $this->request->getPost('content'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // 데이터 삽입 (ID 생성용)
        $this->db->table($tableName)->insert($data);
        $postId = $this->db->insertID(); // 생성된 게시글 ID

        // 파일 및 이미지 업로드 처리
        $data['file_path'] = $this->uploadFile('file', $tableName, $postId, 'files');
        $data['image_path'] = $this->uploadFile('image', $tableName, $postId, 'images');

        // 업로드된 경로 업데이트
        $this->db->table($tableName)->where('id', $postId)->update($data);

        return redirect()->to("/board/{$tableName}")->with('message', '글이 저장되었습니다.');
    }


    // 글 수정 페이지
    public function edit($tableName, $id)
    {
        $post = $this->db->table($tableName)->where('id', $id)->get()->getRowArray();

        if (!$post) {
            return redirect()->to("/board/{$tableName}")->with('error', '존재하지 않는 게시글입니다.');
        }

        return view('board/edit', ['post' => $post, 'tableName' => $tableName]);
    }


    // 게시글 수정 처리
    public function update($tableName, $id)
    {
        $login_id = session()->get('user_id');
        $role = session()->get('role');

        $data = [
            'subject' => $this->request->getPost('subject'),
            'content' => $this->request->getPost('content'),
        ];

        // 기존 게시글 정보 가져오기
        $post = $this->db->table($tableName)->where('id', $id)->get()->getRowArray();

        if ($post['user_id'] !== $login_id && $role !== 'admin') {
            return redirect()->back()->with('error', '수정 권한이 없습니다.');
        }

        // 새 파일 및 이미지 업로드 처리
        $data['file_path'] = $this->uploadFile('file', $tableName, $id, 'files') ?: $post['file_path'];
        $data['image_path'] = $this->uploadFile('image', $tableName, $id, 'images') ?: $post['image_path'];

        // 이미지 및 파일 삭제 요청 처리
        if ($this->request->getPost('delete_image')) {
            $this->deleteFile(WRITEPATH . $post['image_path']);
            $data['image_path'] = null;
        }
        if ($this->request->getPost('delete_file')) {
            $this->deleteFile(WRITEPATH . $post['file_path']);
            $data['file_path'] = null;
        }

        // 데이터 업데이트
        $this->db->table($tableName)->where('id', $id)->update($data);

        return redirect()->to("/board/{$tableName}")->with('message', '글이 수정되었습니다.');
    }



    // 파일 업로드 처리
    private function uploadFile($inputName, $boardName, $postId, $uploadType = 'files')
    {
        $file = $this->request->getFile($inputName);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newFileName = $file->getRandomName(); // 랜덤 파일 이름 생성
            $relativePath = "uploads/{$boardName}/{$postId}/{$uploadType}"; // 상대 경로
            $absolutePath = WRITEPATH . $relativePath; // 절대 경로

            // 디렉토리 생성
            if (!is_dir($absolutePath)) {
                mkdir($absolutePath, 0755, true);
            }

            // 파일 이동
            $file->move($absolutePath, $newFileName);

            return "{$relativePath}/{$newFileName}"; // 파일 경로 반환
        }

        return null;
    }


    // 글 삭제
    public function delete($tableName, $id)
    {
        // 게시글 정보 가져오기
        $post = $this->db->table($tableName)->where('id', $id)->get()->getRowArray();

        if (!$post) {
            return redirect()->to("/board/{$tableName}")->with('error', '존재하지 않는 게시글입니다.');
        }

        // 로그인한 사용자 정보 가져오기
        $login_id = session()->get('user_id'); // 로그인한 사용자 ID
        $role = session()->get('role'); // 사용자 역할 (admin 또는 user)

        // 권한 체크: 작성자 본인 또는 관리자만 삭제 가능
        if ($post['user_id'] != $login_id && $role != 'admin') {
            return redirect()->to("/board/{$tableName}")->with('error', '삭제 권한이 없습니다.');
        }

        // 파일 및 이미지 삭제
        $this->deleteFile($post['file_path']);
        $this->deleteFile($post['image_path']);

        // 게시글 삭제
        $this->db->table($tableName)->where('id', $id)->delete();

        return redirect()->to("/board/{$tableName}")->with('message', '글이 삭제되었습니다.');
    }

    //선택 삭제
    public function deleteSelected($tableName)
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $role = session()->get('role'); // 관리자 권한 확인

        if ($role != 'admin') {
            return redirect()->to("/board/{$tableName}")->with('error', '관리자만 삭제할 수 있습니다.');
        }

        if (!empty($selectedIds)) {
            // 선택된 게시글 삭제
            foreach ($selectedIds as $id) {
                $post = $this->db->table($tableName)->where('id', $id)->get()->getRowArray();

                if ($post) {
                    $this->deleteFile($post['file_path']);
                    $this->deleteFile($post['image_path']);
                    $this->db->table($tableName)->where('id', $id)->delete();
                }
            }
            return redirect()->to("/board/{$tableName}")->with('message', '선택된 게시글이 삭제되었습니다.');
        }

        return redirect()->to("/board/{$tableName}")->with('error', '삭제할 게시글을 선택하세요.');
    }



    // 폼 데이터 수집
    private function collectPostData()
    {
        return [
            'subject' => $this->request->getPost('subject'),
            'content' => $this->request->getPost('content'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }

    // 파일 삭제 처리
    private function deleteFile($filePath)
    {
        if (!empty($filePath) && is_file($filePath)) { // 파일인지 확인
            unlink($filePath); // 파일 삭제
        }
    }


    public function serveImage($boardName, $postId, $type, $fileName)
    {
        $filePath = WRITEPATH . "uploads/{$boardName}/{$postId}/{$type}/{$fileName}";

        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("이미지를 찾을 수 없습니다.");
        }

        return $this->response
            ->setContentType(mime_content_type($filePath))
            ->setBody(file_get_contents($filePath));
    }


    public function downloadFile($boardName, $postId, $type, $fileName)
    {
        // 파일 경로 설정
        $filePath = WRITEPATH . "uploads/{$boardName}/{$postId}/{$type}/{$fileName}";

        // 파일 존재 여부 확인
        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("파일을 찾을 수 없습니다.");
        }

        // 파일 이름 설정
        $basename = basename($filePath);

        // 강제 다운로드 헤더 설정
        return $this->response
            ->setHeader('Content-Type', 'application/octet-stream') // 강제 다운로드를 위한 MIME 타입
            ->setHeader('Content-Disposition', 'attachment; filename="' . $basename . '"') // 파일 이름 설정
            ->setHeader('Expires', '0')
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->setHeader('Pragma', 'public')
            ->setHeader('Content-Length', filesize($filePath)) // 파일 크기 지정
            ->setBody(file_get_contents($filePath)); // 파일 데이터 반환
    }








}
