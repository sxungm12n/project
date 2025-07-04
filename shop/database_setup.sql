-- 충주 사과 쇼핑몰 데이터베이스 스키마
-- 이 파일을 MySQL에서 실행하여 데이터베이스를 설정하세요

-- 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS shopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopdb;

-- 사용자 테이블
CREATE TABLE IF NOT EXISTS usertbl (
    MemberID INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(255) UNIQUE NOT NULL,
    userPassword VARCHAR(255) NOT NULL,
    Nickname VARCHAR(255) NOT NULL,
    mobile1 CHAR(3) DEFAULT NULL,
    mobile2 CHAR(8) DEFAULT NULL,
    isAdmin TINYINT(1) DEFAULT 0
);

-- 상품 테이블
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255) DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT DEFAULT 0,
    seller_name VARCHAR(255) DEFAULT NULL,
    seller_phone VARCHAR(15) NOT NULL,
    seller_address TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (seller_id) REFERENCES usertbl(MemberID)
);

-- 주문 테이블
CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    buyer_name VARCHAR(255) NOT NULL,
    buyer_phone VARCHAR(15) NOT NULL,
    buyer_address TEXT NOT NULL,
    quantity INT NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    seller_name VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- 커뮤니티 게시글 테이블
CREATE TABLE IF NOT EXISTS poststbl (
    postID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES usertbl(MemberID)
);

-- 인덱스 추가
ALTER TABLE purchases ADD INDEX idx_status (status);
ALTER TABLE purchases ADD INDEX idx_seller_name (seller_name);

-- 샘플 데이터 삽입

-- 관리자 계정 (비밀번호: admin123)
INSERT INTO usertbl (userName, userPassword, Nickname, mobile1, mobile2, isAdmin) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '관리자', '010', '12345678', 1);

-- 일반 사용자 계정 (비밀번호: user123)
INSERT INTO usertbl (userName, userPassword, Nickname, mobile1, mobile2) VALUES 
('user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '홍길동', '010', '12345678'),
('seller1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '김판매', '010', '98765432');

-- 샘플 상품 데이터
INSERT INTO products (seller_id, product_name, price, quantity, seller_name, seller_phone, seller_address) VALUES 
(3, '충주 홍로 사과', 15000.00, 50, 'seller1', '010-9876-5432', '충주시 사과농장 456'),
(3, '충주 양광 사과', 12000.00, 30, 'seller1', '010-9876-5432', '충주시 사과농장 456'),
(3, '충주 후지 사과', 18000.00, 25, 'seller1', '010-9876-5432', '충주시 사과농장 456'),
(3, '충주 감홍 사과', 16000.00, 0, 'seller1', '010-9876-5432', '충주시 사과농장 456');

-- 샘플 커뮤니티 게시글
INSERT INTO poststbl (userID, title, content) VALUES 
(2, '충주 사과 맛있어요!', '오늘 충주 사과를 먹어봤는데 정말 맛있었습니다. 다음에도 구매하고 싶어요.'),
(2, '사과 재배 팁 공유', '충주에서 사과를 재배하는 방법에 대해 공유하고 싶습니다.'),
(3, '올해 사과 수확량', '올해는 날씨가 좋아서 사과 수확량이 많을 것 같습니다.');

-- 샘플 주문
INSERT INTO purchases (product_id, buyer_name, buyer_phone, buyer_address, quantity, status, seller_name) VALUES 
(1, 'user1', '010-1234-5678', '서울시 강남구', 2, 'approved', 'seller1'),
(2, 'user1', '010-1234-5678', '서울시 강남구', 1, 'pending', 'seller1'); 