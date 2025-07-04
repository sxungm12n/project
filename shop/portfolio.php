<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>온라인 쇼핑몰 프로젝트 포트폴리오</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 120px 0;
            text-align: center;
        }
        
        .section {
            padding: 80px 0;
        }
        
        .section:nth-child(even) {
            background-color: #ffffff;
        }
        
        .section:nth-child(odd) {
            background-color: #f8f9fa;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .skill-badge {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            margin: 8px;
            display: inline-block;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(52, 73, 94, 0.3);
        }
        
        .feature-icon {
            font-size: 3.5rem;
            color: #34495e;
            margin-bottom: 25px;
        }
        
        .timeline {
            position: relative;
            padding: 30px 0;
        }
        
        .timeline-item {
            padding: 25px;
            border-left: 4px solid #34495e;
            margin-bottom: 25px;
            position: relative;
            background: white;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 30px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #34495e;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #34495e;
        }
        
        .db-table {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border-left: 4px solid #34495e;
        }
        
        .db-table h5 {
            color: #34495e;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 25px 0;
            justify-content: center;
        }
        
        .contact-info {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 50px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(52, 73, 94, 0.3);
        }
        
        h2 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 30px;
        }
        
        h3, h4, h5 {
            color: #34495e;
            font-weight: 600;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .list-unstyled li {
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .list-unstyled li:last-child {
            border-bottom: none;
        }
        
        .db-table ul li {
            padding: 6px 0;
            color: #555;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .hero-section h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hero-section .lead {
            font-size: 1.25rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 mb-4">
                <i class="fas fa-shopping-cart"></i> 온라인 쇼핑몰 프로젝트
            </h1>
            <p class="lead mb-4">PHP, MySQL을 활용한 풀스택 웹 개발 프로젝트</p>
            <div class="tech-stack justify-content-center">
                <span class="skill-badge"><i class="fab fa-php"></i> PHP</span>
                <span class="skill-badge"><i class="fas fa-database"></i> MySQL</span>
                <span class="skill-badge"><i class="fab fa-html5"></i> HTML5</span>
                <span class="skill-badge"><i class="fab fa-css3-alt"></i> CSS3</span>
                <span class="skill-badge"><i class="fab fa-js"></i> JavaScript</span>
                <span class="skill-badge"><i class="fab fa-bootstrap"></i> Bootstrap</span>
            </div>
        </div>
    </section>

    <!-- Project Overview -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-project-diagram"></i> 프로젝트 개요
            </h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-bullseye feature-icon"></i>
                            <h4>프로젝트 목표</h4>
                            <p>사용자 친화적인 온라인 쇼핑몰 플랫폼 구축으로, 판매자와 구매자 간의 원활한 거래 환경 제공</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users feature-icon"></i>
                            <h4>타겟 사용자</h4>
                            <p>일반 구매자, 상품 판매자, 시스템 관리자를 위한 종합적인 쇼핑몰 서비스</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-star"></i> 주요 기능
            </h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-shield feature-icon"></i>
                            <h5>사용자 관리</h5>
                            <ul class="list-unstyled">
                                <li>회원가입/로그인</li>
                                <li>사용자 권한 관리</li>
                                <li>개인정보 관리</li>
                                <li>관리자 기능</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-box feature-icon"></i>
                            <h5>상품 관리</h5>
                            <ul class="list-unstyled">
                                <li>상품 등록/수정/삭제</li>
                                <li>이미지 업로드</li>
                                <li>재고 관리</li>
                                <li>품절 상태 표시</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-bag feature-icon"></i>
                            <h5>주문 관리</h5>
                            <ul class="list-unstyled">
                                <li>상품 구매</li>
                                <li>주문 상태 추적</li>
                                <li>판매자 승인 시스템</li>
                                <li>구매 내역 관리</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Database Design -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-database"></i> 데이터베이스 설계
            </h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="db-table">
                        <h5><i class="fas fa-users"></i> usertbl (사용자)</h5>
                        <ul>
                            <li>MemberID (PK)</li>
                            <li>userName, userPassword</li>
                            <li>Nickname, mobile1, mobile2</li>
                            <li>isAdmin (권한 관리)</li>
                        </ul>
                    </div>
                    <div class="db-table">
                        <h5><i class="fas fa-box"></i> products (상품)</h5>
                        <ul>
                            <li>id (PK), seller_id (FK)</li>
                            <li>product_name, price</li>
                            <li>quantity (재고)</li>
                            <li>seller_name, seller_phone, seller_address</li>
                            <li>product_image</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="db-table">
                        <h5><i class="fas fa-shopping-cart"></i> purchases (구매)</h5>
                        <ul>
                            <li>id (PK), product_id (FK)</li>
                            <li>buyer_name, buyer_phone, buyer_address</li>
                            <li>quantity, purchase_date</li>
                            <li>seller_name</li>
                            <li>status (pending/approved/rejected)</li>
                        </ul>
                    </div>
                    <div class="db-table">
                        <h5><i class="fas fa-comments"></i> poststbl (게시글)</h5>
                        <ul>
                            <li>postID (PK), userID (FK)</li>
                            <li>title, content</li>
                            <li>image_path</li>
                            <li>created_at</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Development Process -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-code-branch"></i> 개발 과정
            </h2>
            <div class="timeline">
                <div class="timeline-item">
                    <h5>1단계: 기본 구조 설계</h5>
                    <p>데이터베이스 스키마 설계 및 기본 사용자 인증 시스템 구축</p>
                </div>
                <div class="timeline-item">
                    <h5>2단계: 상품 관리 시스템</h5>
                    <p>상품 등록, 수정, 삭제 기능 및 이미지 업로드 시스템 구현</p>
                </div>
                <div class="timeline-item">
                    <h5>3단계: 구매 시스템</h5>
                    <p>상품 구매, 장바구니, 결제 프로세스 구현</p>
                </div>
                <div class="timeline-item">
                    <h5>4단계: 주문 관리 시스템</h5>
                    <p>판매자 승인 시스템 및 주문 상태 관리 기능 추가</p>
                </div>
                <div class="timeline-item">
                    <h5>5단계: UI/UX 개선</h5>
                    <p>품절 상태 표시, 반응형 디자인, 사용자 경험 개선</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Highlights -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-lightbulb"></i> 기술적 하이라이트
            </h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-shield-alt"></i> 보안</h5>
                            <ul>
                                <li>비밀번호 해싱 (password_hash)</li>
                                <li>SQL Injection 방지</li>
                                <li>XSS 공격 방지</li>
                                <li>세션 관리</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-mobile-alt"></i> 사용자 경험</h5>
                            <ul>
                                <li>반응형 웹 디자인</li>
                                <li>실시간 재고 확인</li>
                                <li>직관적인 네비게이션</li>
                                <li>품절 상태 자동 표시</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Files -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-folder-open"></i> 프로젝트 파일 구조
            </h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>핵심 파일</h5>
                            <ul>
                                <li><strong>index.php</strong> - 메인 페이지</li>
                                <li><strong>store.php</strong> - 상품 목록/상세</li>
                                <li><strong>checkout.php</strong> - 구매 처리</li>
                                <li><strong>mypage.php</strong> - 사용자 관리</li>
                                <li><strong>seller.php</strong> - 판매자 페이지</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>디렉토리 구조</h5>
                            <ul>
                                <li><strong>login/</strong> - 인증 관련</li>
                                <li><strong>css/</strong> - 스타일시트</li>
                                <li><strong>js/</strong> - 자바스크립트</li>
                                <li><strong>uploads/</strong> - 이미지 파일</li>
                                <li><strong>img/</strong> - 정적 이미지</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="section">
        <div class="container">
            <div class="contact-info">
                <h3><i class="fas fa-envelope"></i> 프로젝트 정보</h3>
                <p class="mb-3">PHP/MySQL 기반 온라인 쇼핑몰 프로젝트</p>
                <div class="row">
                    <div class="col-md-4">
                        <h5><i class="fas fa-calendar"></i> 개발 기간</h5>
                        <p>2024년 7월</p>
                    </div>
                    <div class="col-md-4">
                        <h5><i class="fas fa-code"></i> 개발 언어</h5>
                        <p>PHP, MySQL, HTML/CSS/JS</p>
                    </div>
                    <div class="col-md-4">
                        <h5><i class="fas fa-server"></i> 서버 환경</h5>
                        <p>XAMPP (Apache + MySQL)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 