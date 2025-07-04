<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppRoad - 로그인/회원가입</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            font-family: 'SUITE', 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .header-section {
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            padding: 40px 30px 30px;
            text-align: center;
            color: white;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
        }
        
        .logo::before {
            content: '🍎';
            font-size: 40px;
        }
        
        .brand-name {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: 2px;
        }
        
        .tagline {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .tab-container {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .tab {
            flex: 1;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: #6c757d;
            position: relative;
        }
        
        .tab.active {
            color: #4CAF50;
            background: white;
        }
        
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #4CAF50;
        }
        
        .tab:hover:not(.active) {
            background: #e9ecef;
            color: #495057;
        }
        
        .form-container {
            padding: 40px 30px;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-size: 0.9rem;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            background: #fff;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        
        .mobile-group {
            display: flex;
            gap: 10px;
        }
        
        .mobile-group .form-group {
            flex: 1;
        }
        
        .mobile-group input {
            text-align: center;
        }
        
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }
        
        .home-btn {
            display: block;
            width: 100%;
            background: #f8f9fa;
            color: #495057;
            padding: 14px;
            margin-top: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .home-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
            transform: translateY(-1px);
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 4px;
            display: none;
        }
        
        .form-group.error input {
            border-color: #dc3545;
        }
        
        .form-group.error .error-message {
            display: block;
        }
        
        
        
        @media (max-width: 480px) {
            .auth-container {
                margin: 10px;
            }
            
            .header-section {
                padding: 30px 20px 20px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .brand-name {
                font-size: 1.8rem;
            }
            
            .logo {
                width: 60px;
                height: 60px;
            }
            
            .logo::before {
                font-size: 30px;
            }
            
            .mobile-group {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="header-section">
            <div class="logo"></div>
            <div class="brand-name">AppRoad</div>
            <div class="tagline">당신의 일상을 더 스마트하게</div>
        </div>
        
        <div class="tab-container">
            <div class="tab active" onclick="switchTab('login')">로그인</div>
            <div class="tab" onclick="switchTab('signup')">회원가입</div>
        </div>
        
        <div class="form-container">
            <!-- 로그인 폼 -->
            <div class="form-section active" id="login-form">
                <div class="form-title">로그인</div>
                <form action="login_process.php" method="post">
                    <div class="form-group">
                        <label for="login-username">사용자명</label>
                        <input type="text" id="login-username" name="username" required placeholder="사용자명을 입력하세요">
                    </div>
                    
                    <div class="form-group">
                        <label for="login-password">비밀번호</label>
                        <input type="password" id="login-password" name="password" required placeholder="비밀번호를 입력하세요">
                    </div>
                    
                                         <button type="submit" class="submit-btn">로그인</button>
                 </form>
            </div>
            
            <!-- 회원가입 폼 -->
            <div class="form-section" id="signup-form">
                <div class="form-title">회원가입</div>
                <form action="register.php" method="post" onsubmit="return checkPasswordMatch();">
                    <div class="form-group">
                        <label for="signup-username">사용자명</label>
                        <input type="text" id="signup-username" name="username" required placeholder="사용자명을 입력하세요">
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-password">비밀번호</label>
                        <input type="password" id="signup-password" name="password" required placeholder="비밀번호를 입력하세요">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm-password">비밀번호 확인</label>
                        <input type="password" id="confirm-password" name="confirm_password" required placeholder="비밀번호를 다시 입력하세요">
                        <div class="error-message" id="password-error">비밀번호가 일치하지 않습니다.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nickname">닉네임</label>
                        <input type="text" id="nickname" name="nickname" required placeholder="닉네임을 입력하세요">
                    </div>
                    
                    <div class="mobile-group">
                        <div class="form-group">
                            <label for="mobile1">휴대폰 번호</label>
                            <input type="text" id="mobile1" name="mobile1" placeholder="010" required maxlength="3">
                        </div>
                        <div class="form-group">
                            <label for="mobile2">&nbsp;</label>
                            <input type="text" id="mobile2" name="mobile2" placeholder="12345678" required maxlength="8">
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">회원가입</button>
                </form>
            </div>
        </div>
        
        <a href="../index.php" class="home-btn">🏠 홈으로 돌아가기</a>
    </div>

    <script>
    function switchTab(tabName) {
        // 탭 버튼 상태 변경
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // 폼 섹션 변경
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });
        
        if (tabName === 'login') {
            document.getElementById('login-form').classList.add('active');
        } else {
            document.getElementById('signup-form').classList.add('active');
        }
    }
    
    function checkPasswordMatch() {
        var pw = document.getElementById('signup-password').value;
        var cpw = document.getElementById('confirm-password').value;
        var passwordGroup = document.getElementById('confirm-password').closest('.form-group');
        var errorMessage = document.getElementById('password-error');
        
        if (pw !== cpw) {
            passwordGroup.classList.add('error');
            errorMessage.style.display = 'block';
            document.getElementById('confirm-password').focus();
            return false;
        } else {
            passwordGroup.classList.remove('error');
            errorMessage.style.display = 'none';
        }
        return true;
    }
    
    // 실시간 비밀번호 확인
    document.getElementById('confirm-password').addEventListener('input', function() {
        var pw = document.getElementById('signup-password').value;
        var cpw = this.value;
        var passwordGroup = this.closest('.form-group');
        var errorMessage = document.getElementById('password-error');
        
        if (cpw && pw !== cpw) {
            passwordGroup.classList.add('error');
            errorMessage.style.display = 'block';
        } else {
            passwordGroup.classList.remove('error');
            errorMessage.style.display = 'none';
        }
    });
    
    // 휴대폰 번호 입력 제한
    document.getElementById('mobile1').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    document.getElementById('mobile2').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    </script>
</body>
</html>
