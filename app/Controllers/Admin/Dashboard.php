<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel; // UserModel 가져오기
use App\Models\ProductModel; // ProductModel 가져오기

class Dashboard extends BaseController
{
    /**
     * 대시보드 페이지
     */
    public function index()
    {
        $userModel = new UserModel(); // UserModel 인스턴스화
        $productModel = new ProductModel(); // ProductModel 인스턴스화

        // 사용자와 상품 통계 데이터 가져오기
        $totalUsers = $userModel->countAll(); // 사용자 수
        $totalProducts = $productModel->countAll(); // 상품 수

        // 뷰 데이터 전달
        $data = [
            'title' => '관리자 대시보드',
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
        ];

        echo view('templates/admin_header', $data);
        echo view('admin/dashboard', $data);
        echo view('templates/admin_footer');
    }

    /**
     * 사용자 관리 페이지
     */
    public function users()
    {
        $userModel = new UserModel();

        // 사용자 데이터 가져오기
        $users = $userModel->findAll(); // 모든 사용자 조회

        // 사용자 관리 뷰 렌더링
        echo view('templates/admin_header', ['title' => '사용자 관리']);
        echo view('admin/users', ['users' => $users]); // 사용자 관리 뷰
        echo view('templates/admin_footer');
    }

    /**
     * 상품 관리 페이지 (템플릿만 추가)
     */
    public function products()
    {
        echo view('templates/admin_header', ['title' => '상품 관리']);
        echo view('admin/products'); // 상품 관리 뷰
        echo view('templates/admin_footer');
    }
}
