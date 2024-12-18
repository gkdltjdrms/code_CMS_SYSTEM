<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Install extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        try {
            // 테이블 생성 스크립트
            $tables = [
                "CREATE TABLE `board_list` (
                    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `board_name` VARCHAR(255) NOT NULL COMMENT '게시판 이름',
                    `board_table` VARCHAR(255) NOT NULL COMMENT '게시판 테이블',
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

                "CREATE TABLE `default` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
                    `key` VARCHAR(255) NOT NULL COMMENT '설정 키',
                    `value` VARCHAR(255) NOT NULL COMMENT '설정 값',
                    `company_info` TEXT DEFAULT NULL COMMENT '회사 소개',
                    `business_number` VARCHAR(20) DEFAULT NULL COMMENT '사업자 번호',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

                "CREATE TABLE `migrations` (
                    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `version` VARCHAR(255) NOT NULL COMMENT '버전',
                    `class` VARCHAR(255) NOT NULL COMMENT '클래스',
                    `group` VARCHAR(255) NOT NULL COMMENT '그룹',
                    `namespace` VARCHAR(255) NOT NULL COMMENT '네임스페이스',
                    `time` INT(11) NOT NULL COMMENT '시간',
                    `batch` INT(11) UNSIGNED NOT NULL COMMENT '배치',
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

                "CREATE TABLE `products` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
                    `name` VARCHAR(150) NOT NULL COMMENT '상품 이름',
                    `price` DECIMAL(10,2) NOT NULL COMMENT '가격',
                    `stock` INT(11) NOT NULL DEFAULT 0 COMMENT '재고',
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시',
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

                "CREATE TABLE `users` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
                    `username` VARCHAR(100) NOT NULL COMMENT '사용자 이름',
                    `user_id` VARCHAR(255) NOT NULL COMMENT '아이디',
                    `email` VARCHAR(150) NOT NULL COMMENT '이메일',
                    `password` VARCHAR(255) NOT NULL COMMENT '비밀번호',
                    `role` ENUM('user','admin') DEFAULT 'user' COMMENT '역할',
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `username` (`username`),
                    UNIQUE KEY `email` (`email`),
                    UNIQUE KEY `unique_user_id` (`user_id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
            ];

            // 테이블 생성 실행
            foreach ($tables as $sql) {
                $this->db->query($sql);
            }

            return view('install/success');
        } catch (\Exception $e) {
            return view('install/error', ['error' => $e->getMessage()]);
        }
    }
}
