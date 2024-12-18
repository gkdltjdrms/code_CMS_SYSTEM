<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // layouts/main.php에서 content 섹션으로 출력
        return $this->render('layouts/main', [
            'content' => view('home/index'),
            'title' => '홈 페이지'
        ]);
    }
}
