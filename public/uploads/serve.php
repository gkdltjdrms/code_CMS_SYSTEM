<?php
// 요청된 파일 경로 가져오기
$path = $_GET['path'] ?? '';

if (empty($path)) {
    header("HTTP/1.0 404 Not Found");
    echo "File not found.";
    exit;
}

// 실제 파일 경로 설정
$filePath = realpath(__DIR__ . "/../writable/uploads/{$path}");

if (!$filePath || !file_exists($filePath)) {
    header("HTTP/1.0 404 Not Found");
    echo "File not found.";
    exit;
}

// MIME 타입 감지
$mimeType = mime_content_type($filePath);
header("Content-Type: {$mimeType}");
header("Content-Length: " . filesize($filePath));

// 파일 출력
readfile($filePath);
exit;
