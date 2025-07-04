# 🍎 충주 사과 쇼핑몰

충주 사과를 판매하는 온라인 쇼핑몰 웹사이트입니다.

## 🚀 주요 기능

- **사용자 관리**: 회원가입, 로그인, 로그아웃
- **상품 관리**: 상품 등록, 수정, 삭제, 품절 표시
- **주문 관리**: 주문 생성, 상태 관리 (대기/승인/거절)
- **커뮤니티**: 게시글 작성, 댓글, 좋아요
- **관리자 기능**: 사용자 관리, 상품 관리, 주문 관리
- **날씨 정보**: OpenWeatherMap API 연동 (선택사항)

## 📋 설치 및 설정

### 1. 환경 요구사항
- PHP 7.4 이상
- MySQL 5.7 이상
- Apache/Nginx 웹서버

### 2. 데이터베이스 설정
1. MySQL에서 새 데이터베이스 생성
2. `database_setup.sql` 파일을 MySQL에서 실행하여 테이블과 샘플 데이터 생성
3. `config.example.php`를 `config.php`로 복사하고 데이터베이스 연결 정보 수정:
   ```php
   'database' => [
       'host' => 'localhost',
       'username' => 'your_username',
       'password' => 'your_password',
       'dbname' => 'shopdb'
   ]
   ```

### 3. API 키 설정 (선택사항)
날씨 기능을 사용하려면:

1. [OpenWeatherMap](https://openweathermap.org/api)에서 무료 API 키 발급
2. `config.php`에서 API 키 입력:
   ```php
   'api_key' => 'YOUR_ACTUAL_API_KEY_HERE'
   ```

**⚠️ 중요**: `config.php` 파일은 `.gitignore`에 포함되어 GitHub에 업로드되지 않습니다.

### 4. 파일 업로드 설정
- `uploads/products/` 폴더에 쓰기 권한 부여
- 최대 업로드 파일 크기 설정 (php.ini)

### 5. 기본 계정 정보
데이터베이스 설정 후 사용할 수 있는 기본 계정:
- **관리자**: `admin` / `admin123`
- **일반 사용자**: `user1` / `user123`
- **판매자**: `seller1` / `user123`

## 🗂️ 프로젝트 구조

```
shop/
├── css/                    # 스타일시트
├── img/                    # 이미지 파일
├── js/                     # 자바스크립트
├── login/                  # 로그인 관련 파일
├── uploads/                # 업로드된 파일
├── config.php              # API 키 및 DB 설정 (GitHub에 업로드 안됨)
├── config.example.php      # 설정 파일 예시
├── database_setup.sql      # 데이터베이스 스키마 및 샘플 데이터
├── index.php              # 메인 페이지
├── store.php              # 상점 페이지
├── community.php          # 커뮤니티
├── mypage.php             # 마이페이지
├── portfolio.php          # 프로젝트 포트폴리오
└── weather.php            # 날씨 정보
```

## 🔧 데이터베이스 구조

### 주요 테이블
- `users`: 사용자 정보 (일반 사용자, 판매자, 관리자)
- `products`: 상품 정보 (이름, 가격, 수량, 카테고리)
- `purchases`: 주문 정보 (상태: 대기/승인/거절)
- `posts`: 커뮤니티 게시글
- `comments`: 댓글
- `likes`: 좋아요

## 🎨 사용된 기술

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **API**: OpenWeatherMap (날씨 정보)

## 📝 라이선스

이 프로젝트는 교육 목적으로 제작되었습니다.

## 🤝 기여하기

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 문의

프로젝트에 대한 문의사항이 있으시면 이슈를 생성해 주세요. 
