<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>🍎 충주 사과 쇼핑몰</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      color: #333;
      line-height: 1.6;
    }
    .section {
      padding: 60px 0;
    }
    .section:nth-child(even) {
      background-color: #fff;
    }
    .section:nth-child(odd) {
      background-color: #f1f3f5;
    }
    .icon-title {
      color: #e74c3c;
      margin-right: 10px;
    }
    .code-block {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      font-family: monospace;
      white-space: pre-wrap;
    }
  </style>
</head>
<body>

  <header class="text-center py-5 bg-danger text-white">
    <div class="container">
      <h1 class="display-4"><i class="fa-solid fa-apple-whole"></i> 충주 사과 쇼핑몰</h1>
      <p class="lead mt-3">충주 사과를 판매하는 온라인 쇼핑몰 웹사이트입니다.</p>
    </div>
  </header>

  <section class="section">
    <div class="container">
      <h2><i class="fa-solid fa-bolt icon-title"></i> 주요 기능</h2>
      <ul>
        <li><strong>사용자 관리</strong>: 회원가입, 로그인, 로그아웃</li>
        <li><strong>상품 관리</strong>: 상품 등록, 수정, 삭제, 품절 표시</li>
        <li><strong>주문 관리</strong>: 주문 생성, 상태 관리 (대기/승인/거절)</li>
        <li><strong>커뮤니티</strong>: 게시글 작성, 댓글, 좋아요</li>
        <li><strong>관리자 기능</strong>: 사용자/상품/주문 통합 관리</li>
        <li><strong>날씨 정보</strong>: OpenWeatherMap API 연동 (선택사항)</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2><i class="fa-solid fa-screwdriver-wrench icon-title"></i> 설치 및 설정</h2>
      <h5>1. 환경 요구사항</h5>
      <ul>
        <li>PHP 7.4 이상</li>
        <li>MySQL 5.7 이상</li>
        <li>Apache 또는 Nginx 웹서버</li>
      </ul>

      <h5 class="mt-4">2. 데이터베이스 설정</h5>
      <ol>
        <li>MySQL에서 새 데이터베이스 생성</li>
        <li><code>database_setup.sql</code> 파일 실행</li>
        <li><code>config.example.php</code> → <code>config.php</code>로 복사 후 아래 내용 수정:</li>
      </ol>
      <div class="code-block">
        'database' => [<br>
        &nbsp;&nbsp;'host' => 'localhost',<br>
        &nbsp;&nbsp;'username' => 'your_username',<br>
        &nbsp;&nbsp;'password' => 'your_password',<br>
        &nbsp;&nbsp;'dbname' => 'shopdb'<br>
        ]
      </div>

      <h5 class="mt-4">3. API 키 설정 (선택)</h5>
      <ol>
        <li><a href="https://openweathermap.org/api" target="_blank">OpenWeatherMap</a>에서 API 키 발급</li>
        <li><code>config.php</code>에 다음과 같이 설정</li>
      </ol>
      <div class="code-block">
        'api_key' => 'YOUR_ACTUAL_API_KEY_HERE'
      </div>

      <p class="text-danger mt-2"><strong>⚠️ 참고:</strong> <code>config.php</code>는 <code>.gitignore</code>에 포함되어야 합니다.</p>

      <h5 class="mt-4">4. 파일 업로드 권한</h5>
      <ul>
        <li><code>uploads/products/</code> 폴더에 쓰기 권한 부여</li>
        <li>php.ini 파일에서 업로드 용량 조정 가능</li>
      </ul>

      <h5 class="mt-4">5. 기본 계정 정보</h5>
      <ul>
        <li><strong>관리자</strong>: admin / admin123</li>
        <li><strong>일반 사용자</strong>: user1 / user123</li>
        <li><strong>판매자</strong>: seller1 / user123</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2><i class="fa-solid fa-folder-tree icon-title"></i> 프로젝트 구조</h2>
      <div class="code-block">
shop/<br>
├── css/               # 스타일시트<br>
├── img/               # 이미지 파일<br>
├── js/                # 자바스크립트<br>
├── login/             # 로그인 기능<br>
├── uploads/           # 업로드된 파일<br>
├── config.php         # 환경 설정 (비공개)<br>
├── config.example.php # 설정 예시<br>
├── database_setup.sql # DB 스키마<br>
├── index.php          # 메인 페이지<br>
├── store.php          # 상품 페이지<br>
├── community.php      # 커뮤니티<br>
├── mypage.php         # 마이페이지<br>
├── portfolio.php      # 포트폴리오 소개<br>
└── weather.php        # 날씨 정보 API
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2><i class="fa-solid fa-database icon-title"></i> 데이터베이스 구조</h2>
      <ul>
        <li><strong>usertbl</strong>: 사용자 정보 (권한 포함)</li>
        <li><strong>products</strong>: 상품 정보 (가격, 수량, 판매자 등)</li>
        <li><strong>purchases</strong>: 주문 정보 (상태: pending/approved/rejected)</li>
        <li><strong>poststbl</strong>: 커뮤니티 게시글</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2><i class="fa-solid fa-code icon-title"></i> 사용 기술</h2>
      <ul>
        <li><strong>Frontend</strong>: HTML5, CSS3, JavaScript, Bootstrap 5</li>
        <li><strong>Backend</strong>: PHP 7.4+</li>
        <li><strong>Database</strong>: MySQL</li>
        <li><strong>API</strong>: OpenWeatherMap</li>
      </ul>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2><i class="fa-solid fa-handshake icon-title"></i> 라이선스 및 기여</h2>
      <p><strong>라이선스:</strong> 본 프로젝트는 교육 목적입니다.</p>
      <p><strong>기여 방법:</strong></p>
      <ol>
        <li>레포를 Fork 합니다</li>
        <li>기능 브랜치 생성: <code>git checkout -b feature/YourFeature</code></li>
        <li>변경 사항 커밋: <code>git commit -m 'Add YourFeature'</code></li>
        <li>푸시: <code>git push origin feature/YourFeature</code></li>
        <li>Pull Request 생성</li>
      </ol>
    </div>
  </section>

  <footer class="bg-dark text-white text-center py-4">
    <p class="mb-0">문의사항은 GitHub 이슈로 남겨주세요.</p>
  </footer>

</body>
</html>
