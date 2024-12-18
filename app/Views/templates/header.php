<!DOCTYPE html>
<html lang="ko">
<head>
    <?php $settings = service('solutionSettings'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<title><?php /*= $title ?? '솔루션 이름' */?></title>-->
    <title><?= $title ?? esc($settings->solution_name) ?></title>
    <!-- Bootstrap 5 CDN 추가 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    <h1><?= esc($settings->solution_name) ?></h1>
    <nav>
        <ul>
            <li><a href="/">홈</a></li>
            <li><a href="/admin">관리자 페이지</a></li>
            <?php if (session()->get('isLoggedIn')): ?>
                <li><a href="/mypage">마이페이지</a></li>
                <li><a href="/auth/logout">로그아웃</a></li>
            <?php else: ?>
                <li><a href="/auth/signup">회원가입</a></li>
                <li><a href="/auth/login">로그인</a></li>
            <?php endif; ?>
            <li><a href="/board">게시판</a></li> <!-- 게시판 메뉴 추가 -->
        </ul>
    </nav>
</header>
<main>
