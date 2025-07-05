-- 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE dbname;

-- 사용자 테이블 (user는 예약어이므로 users로 변경 권장)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) UNIQUE NOT NULL,
    password VARCHAR(128) NOT NULL,
    name VARCHAR(80) NOT NULL,
    birthday DATE NOT NULL,
    gender ENUM('남자', '여자') NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 게시글 테이블
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id)
);

-- 댓글 테이블 (게시글용)
CREATE TABLE post_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (post_id),
    INDEX (user_id)
);

-- 리뷰 테이블
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),  -- photo_path와 통합
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id)
);

-- 리뷰 댓글 테이블
CREATE TABLE review_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (review_id),
    INDEX (user_id)
);

-- 영양제 상세 테이블
CREATE TABLE tonic_detail (
    tonic_number INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    VitaminA FLOAT,
    VitaminD FLOAT,
    VitaminE FLOAT,
    VitaminK FLOAT,
    VitaminC FLOAT,
    Thiamine FLOAT,          -- B1
    Riboflavin FLOAT,        -- B2
    Niacin FLOAT,
    VitaminB6 FLOAT,
    FolicAcid FLOAT,
    VitaminB12 FLOAT,
    PantothenicAcid FLOAT,
    Biotin FLOAT,
    Calcium FLOAT,
    Phosphorus FLOAT,
    Sodium FLOAT,
    Chlorine FLOAT,
    Potassium FLOAT,
    Magnesium FLOAT,
    Iron FLOAT,
    Zinc FLOAT,
    Copper FLOAT,
    Fluorine FLOAT,
    Manganese FLOAT,
    Iodine FLOAT,
    Selenium FLOAT,
    Molybdenum FLOAT,
    Chromium FLOAT,
    dosage INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id)
);
