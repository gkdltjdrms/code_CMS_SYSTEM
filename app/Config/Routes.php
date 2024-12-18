<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// 기본 라우터 설정
$routes->setDefaultNamespace('App\Controllers'); // 기본 컨트롤러 네임스페이스
$routes->setDefaultController('Home');          // 기본 컨트롤러
$routes->setDefaultMethod('index');             // 기본 메서드
$routes->setTranslateURIDashes(false);          // URI에서 대시(-) 변환 비활성화
$routes->set404Override();                      // 404 페이지 설정 (추후 필요 시 작성)
$routes->setAutoRoute(true);                    // 자동 라우팅 활성화

/*
 * 라우팅 정의
 */

// 홈 페이지
$routes->get('/', 'Home::index');

// 회원 관련 라우트
$routes->group('auth', function($routes) {
    $routes->get('signup', 'Auth\Auth::signup');         // 회원가입 페이지
    $routes->post('signup', 'Auth\Auth::processSignup'); // 회원가입 처리
    $routes->get('login', 'Auth\Auth::login');           // 로그인 페이지
    $routes->post('login', 'Auth\Auth::processLogin');   // 로그인 처리
    $routes->get('logout', 'Auth\Auth::logout');         // 로그아웃
});

// 마이페이지 관련 라우팅
$routes->group('mypage', ['namespace' => 'App\Controllers\MyPage'], function ($routes) {
    $routes->get('/', 'MyPage::index'); // 마이페이지 메인
    $routes->get('posts', 'MyPage::posts'); // 작성 글 보기
    $routes->post('deletePost', 'MyPage::deletePost');

});


// 상품 관련 라우트
$routes->group('product', function($routes) {
    $routes->get('list', 'Product\Product::index');  // 상품 목록
    $routes->get('view/(:num)', 'Product\Product::view/$1'); // 특정 상품 상세 보기
});

// 관리자 페이지
$routes->group('admin', ['filter' => 'adminAuth'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index'); // 대시보드
    $routes->get('users', 'Admin\Dashboard::users'); // 사용자 관리
    $routes->get('products', 'Admin\Dashboard::products'); // 상품 관리

    // 게시판 관리
    $routes->get('boards', 'Admin\Board::index');        // 게시판 목록
    $routes->get('boards/create', 'Admin\Board::create'); // 게시판 생성 페이지
    $routes->post('boards/store', 'Admin\Board::store');  // 게시판 생성 처리
    $routes->get('boards/delete/(:num)', 'Admin\Board::delete/$1'); //게시판 삭제
});

// 사용자 게시판 CRUD 라우트
$routes->group('board', function ($routes) {
    $routes->get('(:segment)', 'Board\BoardController::index/$1'); // 게시판 목록
    $routes->get('(:segment)/view/(:num)', 'Board\BoardController::view/$1/$2'); // 게시글 보기
    $routes->get('(:segment)/create', 'Board\BoardController::create/$1'); // 글 작성
    $routes->post('(:segment)/store', 'Board\BoardController::store/$1'); // 글 저장
    $routes->get('(:segment)/edit/(:num)', 'Board\BoardController::edit/$1/$2'); // 글 수정
    $routes->post('(:segment)/update/(:num)', 'Board\BoardController::update/$1/$2'); // 글 업데이트
    $routes->get('(:segment)/delete/(:num)', 'Board\BoardController::delete/$1/$2'); // 글 삭제
});
//게시판 리스트
$routes->get('board', 'Board\BoardController::boardList');
//이미지 경로 지정
$routes->get('image/(:segment)/(:segment)/(:segment)/(:segment)', 'Board\BoardController::serveImage/$1/$2/$3/$4');
$routes->get('download/(:segment)/(:segment)/(:segment)/(:segment)', 'Board\BoardController::downloadFile/$1/$2/$3/$4');


//선택 삭제
$routes->post('board/(:segment)/delete-selected', 'Board\BoardController::deleteSelected/$1'); //글 선택삭제



// 관리자 기본환경 설정 세팅
$routes->get('admin/settings', 'Admin\Settings::index');
$routes->post('admin/settings/update', 'Admin\Settings::update');



// 기타 페이지
$routes->get('about', 'Pages::about'); // About 페이지
$routes->get('contact', 'Pages::contact'); // Contact 페이지


//설치 페이지
$routes->get('install', 'Install::index');


/*
 * 환경별 라우트 파일 포함
 * 환경별로 다른 라우트를 설정할 수 있습니다.
 */
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}
