<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '관리자 페이지' ?></title>
    <!-- Bootstrap 5 CDN 추가 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    <h1>관리자 페이지</h1>
    <nav>
        <ul>
            <li><a href="/">홈</a></li>
            <li><a href="/admin">대시보드</a></li>
            <li><a href="/admin/Settings">환경설정</a></li>
            <li><a href="/admin/users">사용자 관리</a></li>
            <li><a href="/admin/boards">게시판 관리</a></li>
            <li><a href="/admin/products">상품 관리</a></li>
            <li><a href="/auth/logout">로그아웃</a></li>
        </ul>
    </nav>
</header>
<main>
