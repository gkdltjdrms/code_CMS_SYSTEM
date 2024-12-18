<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function about()
    {
        return view('pages/about'); // About 페이지
    }

    public function contact()
    {
        return view('pages/contact'); // Contact 페이지
    }
}
