<div align="center">
  <img src="img/main/apple.jpg" alt="충주 사과 쇼핑몰" width="200" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
  <h1 style="color: #2c3e50; font-size: 3rem; margin: 20px 0; font-weight: 700;">🍎 충주 사과 쇼핑몰</h1>
  <p style="color: #7f8c8d; font-size: 1.2rem; margin-bottom: 30px; max-width: 600px;">
    충주 사과를 판매하는 온라인 쇼핑몰 웹사이트입니다.<br>
    <strong style="color: #34495e;">PHP, MySQL, Bootstrap</strong>을 활용한 풀스택 웹 프로젝트
  </p>
  
  <div style="display: flex; justify-content: center; gap: 15px; margin: 30px 0;">
    <img src="https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
    <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5">
    <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
  </div>
</div>

---

## 📋 프로젝트 개요

<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 30px; border-radius: 15px; margin: 30px 0; border-left: 5px solid #6c757d;">

### 🎯 프로젝트 목표
충주 지역의 특산물인 사과를 온라인으로 판매할 수 있는 쇼핑몰 플랫폼을 구축하여, 지역 농가의 디지털 마케팅 역량을 강화하고 소비자에게는 신선한 지역 특산물을 제공하는 것이 목표입니다.

### 🛠️ 개발 환경
- **개발 기간**: 2024년 12월 ~ 2025년 1월
- **개발 인원**: 1명 (풀스택 개발)
- **개발 도구**: Visual Studio Code, XAMPP, phpMyAdmin
- **버전 관리**: Git

</div>

## 🚀 주요 기능

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">

### 👥 사용자 관리
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #495057;">
- ✅ 회원가입 및 로그인/로그아웃
- ✅ 사용자 권한 관리 (일반/판매자/관리자)
- ✅ 세션 기반 인증 시스템
- ✅ 비밀번호 암호화 (bcrypt)
</div>

### 🛍️ 상품 관리
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #495057;">
- ✅ 상품 등록, 수정, 삭제
- ✅ 이미지 업로드 및 관리
- ✅ 재고 관리 및 품절 표시
- ✅ 카테고리별 상품 분류
</div>

### 📦 주문 관리
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #495057;">
- ✅ 장바구니 및 구매 프로세스
- ✅ 주문 상태 관리 (대기/승인/거절)
- ✅ 판매자별 주문 관리
- ✅ 구매 내역 조회
</div>

### 💬 커뮤니티
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #495057;">
- ✅ 게시글 작성 및 수정
- ✅ 댓글 시스템
- ✅ 좋아요 기능
- ✅ 페이지네이션
</div>

### 👨‍💼 관리자 기능
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #495057;">
- ✅ 사용자 관리 (CRUD)
- ✅ 상품 관리
- ✅ 주문 상태 관리
- ✅ 통계 대시보드
</div>

### 🌤️ 날씨 정보
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #495057;">
- ✅ OpenWeatherMap API 연동
- ✅ 실시간 날씨 정보 표시
- ✅ 농작물 관리 조언 제공
- ✅ API 키 보안 관리
</div>

</div>

## 🗂️ 프로젝트 구조

<div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin: 30px 0; font-family: 'Courier New', monospace;">

```
shop/
├── 📁 css/                    # 스타일시트
│   ├── common.css            # 공통 스타일
│   ├── main.css              # 메인 페이지 스타일
│   └── reset.css             # CSS 리셋
├── 📁 img/                   # 이미지 파일
│   └── main/                 # 메인 이미지들
├── 📁 js/                    # 자바스크립트
├── 📁 login/                 # 로그인 관련 파일
│   ├── admin_index.php       # 관리자 페이지
│   ├── login.php             # 로그인 페이지
│   └── register.php          # 회원가입 페이지
├── 📁 uploads/               # 업로드된 파일
│   └── products/             # 상품 이미지
├── 📄 config.php             # API 키 및 DB 설정 (보안)
├── 📄 config.example.php     # 설정 파일 예시
├── 📄 database_setup.sql     # 데이터베이스 스키마
├── 📄 index.php              # 메인 페이지
├── 📄 store.php              # 상점 페이지
├── 📄 community.php          # 커뮤니티
├── 📄 mypage.php             # 마이페이지
├── 📄 portfolio.php          # 프로젝트 포트폴리오
└── 📄 weather.php            # 날씨 정보
```

</div>

## 🔧 데이터베이스 설계

<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 30px; border-radius: 15px; margin: 30px 0;">

### 📊 ERD (Entity Relationship Diagram)

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   usertbl   │    │  products   │    │  purchases  │
├─────────────┤    ├─────────────┤    ├─────────────┤
│ MemberID    │◄───┤ seller_id   │    │ id          │
│ userName    │    │ id          │◄───┤ product_id  │
│ userPassword│    │ product_name│    │ buyer_name  │
│ Nickname    │    │ price       │    │ quantity    │
│ mobile1     │    │ quantity    │    │ status      │
│ mobile2     │    │ seller_name │    │ seller_name │
│ isAdmin     │    │ seller_phone│    └─────────────┘
└─────────────┘    │ seller_addr │
                   └─────────────┘
                          │
                          ▼
                   ┌─────────────┐
                   │  poststbl   │
                   ├─────────────┤
                   │ postID      │
                   │ userID      │◄───┐
                   │ title       │    │
                   │ content     │    │
                   │ created_at  │    │
                   └─────────────┘    │
                                      │
                   ┌─────────────┐    │
                   │   usertbl   │────┘
                   └─────────────┘
```

### 🗃️ 주요 테이블 설명

| 테이블명 | 설명 | 주요 컬럼 |
|---------|------|-----------|
| `usertbl` | 사용자 정보 | MemberID, userName, userPassword, isAdmin |
| `products` | 상품 정보 | id, product_name, price, quantity, seller_name |
| `purchases` | 주문 정보 | id, product_id, buyer_name, status, seller_name |
| `poststbl` | 커뮤니티 게시글 | postID, userID, title, content, created_at |

</div>

## 🎨 UI/UX 디자인

<div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin: 30px 0;">

### 🎯 디자인 컨셉
- **모던하고 깔끔한 디자인**: Bootstrap 5를 활용한 반응형 디자인
- **사용자 친화적 인터페이스**: 직관적인 네비게이션과 명확한 정보 구조
- **일관된 색상 체계**: 회색 톤을 기반으로 한 전문적인 느낌

### 📱 반응형 디자인
- **데스크톱**: 1200px 이상 - 전체 기능 제공
- **태블릿**: 768px ~ 1199px - 적응형 레이아웃
- **모바일**: 767px 이하 - 모바일 최적화

### 🎨 주요 페이지별 특징
- **메인 페이지**: 충주 사과 소개와 날씨 정보
- **상점 페이지**: 상품 목록과 상세 정보
- **커뮤니티**: 사용자 간 소통 공간
- **마이페이지**: 개인화된 관리 기능

</div>

## 🔒 보안 및 최적화

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">

### 🔐 보안 기능
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #dc3545;">
- ✅ SQL Injection 방지 (Prepared Statements)
- ✅ XSS 공격 방지 (htmlspecialchars)
- ✅ 비밀번호 암호화 (bcrypt)
- ✅ 세션 관리 및 인증
- ✅ API 키 보안 관리
</div>

### ⚡ 성능 최적화
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #28a745;">
- ✅ 데이터베이스 인덱싱
- ✅ 이미지 최적화 및 압축
- ✅ 페이지네이션 구현
- ✅ 캐싱 전략 적용
</div>

### 🛡️ 에러 처리
<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #ffc107;">
- ✅ 사용자 친화적 에러 메시지
- ✅ 로그 기록 및 모니터링
- ✅ 예외 상황 처리
- ✅ 데이터 유효성 검증
</div>

</div>

## 📋 설치 및 설정

<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 30px; border-radius: 15px; margin: 30px 0;">

### 1️⃣ 환경 요구사항
- **PHP**: 7.4 이상
- **MySQL**: 5.7 이상
- **웹서버**: Apache/Nginx
- **브라우저**: Chrome, Firefox, Safari, Edge

### 2️⃣ 데이터베이스 설정
```sql
-- 1. MySQL에서 새 데이터베이스 생성
CREATE DATABASE shopdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. database_setup.sql 파일 실행
mysql -u root -p shopdb < database_setup.sql
```

### 3️⃣ 애플리케이션 설정
```bash
# 1. config.example.php를 config.php로 복사
cp config.example.php config.php

# 2. config.php에서 데이터베이스 정보 수정
'database' => [
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'dbname' => 'shopdb'
]
```

### 4️⃣ API 키 설정 (선택사항)
```php
// config.php에서 OpenWeatherMap API 키 설정
'weather' => [
    'api_key' => 'YOUR_API_KEY_HERE',
    'city' => 'Chungju',
    'units' => 'metric'
]
```

### 5️⃣ 파일 권한 설정
```bash
# uploads 폴더에 쓰기 권한 부여
chmod 755 uploads/products/
```

### 6️⃣ 기본 계정 정보
| 계정 유형 | 아이디 | 비밀번호 |
|----------|--------|----------|
| 관리자 | admin | admin123 |
| 일반 사용자 | user1 | user123 |
| 판매자 | seller1 | user123 |

</div>

## 🚀 배포 가이드

<div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin: 30px 0;">

### 📦 GitHub 배포
```bash
# 1. 저장소 클론
git clone https://github.com/your-username/chungju-apple-shop.git

# 2. 설정 파일 생성
cp config.example.php config.php

# 3. 데이터베이스 설정
mysql -u root -p < database_setup.sql

# 4. 웹서버 설정
# Apache/Nginx 설정 파일 수정
```

### ☁️ 클라우드 배포
- **AWS**: EC2 + RDS + S3
- **Google Cloud**: Compute Engine + Cloud SQL
- **Azure**: App Service + Azure Database

### 🔧 환경 변수 설정
```bash
# .env 파일 생성 (선택사항)
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=your_password
DB_NAME=shopdb
WEATHER_API_KEY=your_api_key
```

</div>

## 📊 프로젝트 통계

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0;">

<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border-left: 4px solid #6c757d;">
<h3 style="color: #495057; margin: 0;">📁 파일 수</h3>
<p style="font-size: 2rem; font-weight: bold; color: #6c757d; margin: 10px 0;">25+</p>
<p style="color: #6c757d; margin: 0;">PHP, CSS, JS 파일</p>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border-left: 4px solid #6c757d;">
<h3 style="color: #495057; margin: 0;">🗃️ 테이블 수</h3>
<p style="font-size: 2rem; font-weight: bold; color: #6c757d; margin: 10px 0;">4</p>
<p style="color: #6c757d; margin: 0;">데이터베이스 테이블</p>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border-left: 4px solid #6c757d;">
<h3 style="color: #495057; margin: 0;">⚡ 기능 수</h3>
<p style="font-size: 2rem; font-weight: bold; color: #6c757d; margin: 10px 0;">15+</p>
<p style="color: #6c757d; margin: 0;">주요 기능들</p>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border-left: 4px solid #6c757d;">
<h3 style="color: #495057; margin: 0;">🔒 보안</h3>
<p style="font-size: 2rem; font-weight: bold; color: #6c757d; margin: 10px 0;">5+</p>
<p style="color: #6c757d; margin: 0;">보안 기능</p>
</div>

</div>

## 🎯 향후 개선 계획

<div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin: 30px 0;">

### 📈 기능 개선
- [ ] 결제 시스템 연동 (PG사)
- [ ] 실시간 채팅 기능
- [ ] 모바일 앱 개발 (React Native)
- [ ] AI 기반 상품 추천 시스템
- [ ] 배송 추적 시스템

### 🔧 기술 개선
- [ ] PHP 8.x 업그레이드
- [ ] Laravel 프레임워크 적용
- [ ] Redis 캐싱 도입
- [ ] Docker 컨테이너화
- [ ] CI/CD 파이프라인 구축

### 📊 분석 및 모니터링
- [ ] Google Analytics 연동
- [ ] 사용자 행동 분석
- [ ] 성능 모니터링
- [ ] 에러 로깅 시스템

</div>

## 🤝 기여하기

<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 30px; border-radius: 15px; margin: 30px 0; text-align: center;">

프로젝트에 기여하고 싶으시다면 언제든 환영합니다! 🎉

### 📝 기여 방법
1. **Fork** the Project
2. **Create** your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your Changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the Branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

### 📞 문의 및 피드백
- **이슈 리포트**: [GitHub Issues](https://github.com/your-username/chungju-apple-shop/issues)
- **기능 제안**: [GitHub Discussions](https://github.com/your-username/chungju-apple-shop/discussions)
- **이메일**: your-email@example.com

</div>

---

<div align="center" style="margin-top: 50px; padding: 30px; background: #f8f9fa; border-radius: 15px;">

### 📄 라이선스
이 프로젝트는 **MIT License** 하에 배포됩니다.

### 🙏 감사의 말
이 프로젝트를 통해 PHP와 웹 개발에 대한 깊은 이해를 얻을 수 있었습니다.<br>
특히 데이터베이스 설계, 보안, 사용자 경험에 대한 실무 경험을 쌓을 수 있어서 매우 유익했습니다.

**Made with ❤️ by [Your Name]**

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/your-username)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/your-profile)

</div> 