<?php

namespace App\Controllers\Product;

use App\Controllers\BaseController;

class Product extends BaseController
{
    public function index()
    {
        return view('product/list'); // 상품 목록 페이지
    }

    public function view($id)
    {
        return view('product/view', ['productId' => $id]); // 특정 상품 상세 페이지
    }
}
