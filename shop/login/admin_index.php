<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || !isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

// 데이터베이스 연결
$conn = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $delete_sql = "DELETE FROM userTBL WHERE MemberID = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $userName = $_POST['userName'];
        $userPassword = $_POST['userPassword'];
        $Nickname = $_POST['Nickname'];
        $mobile1 = $_POST['mobile1'];
        $mobile2 = $_POST['mobile2'];
        $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;
        
        $update_sql = "UPDATE userTBL SET userName = ?, userPassword = ?, Nickname = ?, mobile1 = ?, mobile2 = ?, isAdmin = ? WHERE MemberID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssssii", $userName, $userPassword, $Nickname, $mobile1, $mobile2, $isAdmin, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['add'])) {
        $userName = $_POST['newUserName'];
        $userPassword = password_hash($_POST['newUserPassword'], PASSWORD_DEFAULT);
        $Nickname = $_POST['newNickname'];
        $mobile1 = $_POST['newMobile1'];
        $mobile2 = $_POST['newMobile2'];
        $isAdmin = isset($_POST['newIsAdmin']) ? 1 : 0;

        $add_sql = "INSERT INTO userTBL (userName, userPassword, Nickname, mobile1, mobile2, isAdmin) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($add_sql);
        $stmt->bind_param("sssssi", $userName, $userPassword, $Nickname, $mobile1, $mobile2, $isAdmin);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT * FROM userTBL";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 대시보드</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: #2c3e50;
        }
        
        .admin-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 30px auto;
            overflow: hidden;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .admin-header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 2.5rem;
        }
        
        .admin-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .admin-content {
            padding: 40px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
            border: none;
            padding: 20px 25px;
        }
        
        .card-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #34495e;
            box-shadow: 0 0 0 0.2rem rgba(52, 73, 94, 0.25);
        }
        
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #34495e, #2c3e50);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 73, 94, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }
        
        .btn-outline-secondary {
            border: 2px solid #34495e;
            color: #34495e;
        }
        
        .btn-outline-secondary:hover {
            background: #34495e;
            color: white;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table thead th {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        .table tbody td {
            padding: 15px;
            border: none;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #34495e;
        }
        
        .form-check-input:checked {
            background-color: #34495e;
            border-color: #34495e;
        }
        
        .admin-badge {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }
        
        .user-badge {
            background: linear-gradient(45deg, #95a5a6, #7f8c8d);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stats-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .permission-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .permission-checkbox {
            margin: 0;
        }
        
        .permission-label {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="admin-container">
                    <!-- Header -->
                    <div class="admin-header">
                        <h1><i class="fas fa-user-shield"></i> 관리자 대시보드</h1>
                        <p>시스템 관리 및 사용자 관리</p>
                        <a href="../index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> 홈으로 가기
                        </a>
                    </div>
                    
                    <!-- Content -->
                    <div class="admin-content">
                        <!-- Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="stats-number">
                                        <?php 
                                        $count_sql = "SELECT COUNT(*) as total FROM userTBL";
                                        $count_result = $conn->query($count_sql);
                                        $count_row = $count_result->fetch_assoc();
                                        echo $count_row['total'];
                                        ?>
                                    </div>
                                    <div class="stats-label">총 사용자 수</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="stats-number">
                                        <?php 
                                        $admin_count_sql = "SELECT COUNT(*) as total FROM userTBL WHERE isAdmin = 1";
                                        $admin_count_result = $conn->query($admin_count_sql);
                                        $admin_count_row = $admin_count_result->fetch_assoc();
                                        echo $admin_count_row['total'];
                                        ?>
                                    </div>
                                    <div class="stats-label">관리자 수</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="stats-number">
                                        <?php 
                                        $user_count_sql = "SELECT COUNT(*) as total FROM userTBL WHERE isAdmin = 0";
                                        $user_count_result = $conn->query($user_count_sql);
                                        $user_count_row = $user_count_result->fetch_assoc();
                                        echo $user_count_row['total'];
                                        ?>
                                    </div>
                                    <div class="stats-label">일반 사용자 수</div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New User -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-user-plus"></i> 새로운 사용자 추가</h2>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newUserName" class="form-label">사용자명</label>
                                                <input type="text" id="newUserName" name="newUserName" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newUserPassword" class="form-label">비밀번호</label>
                                                <input type="password" id="newUserPassword" name="newUserPassword" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newNickname" class="form-label">닉네임</label>
                                                <input type="text" id="newNickname" name="newNickname" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newMobile1" class="form-label">전화번호 1</label>
                                                <input type="text" id="newMobile1" name="newMobile1" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newMobile2" class="form-label">전화번호 2</label>
                                                <input type="text" id="newMobile2" name="newMobile2" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-check mt-4">
                                                    <input type="checkbox" id="newIsAdmin" name="newIsAdmin" class="form-check-input">
                                                    <label for="newIsAdmin" class="form-check-label form-label">관리자 권한 부여</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="add" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> 사용자 추가
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- User List -->
                        <div class="card">
                            <div class="card-header">
                                <h2><i class="fas fa-users"></i> 사용자 관리</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>사용자명</th>
                                                <th>비밀번호</th>
                                                <th>닉네임</th>
                                                <th>전화번호 1</th>
                                                <th>전화번호 2</th>
                                                <th>권한</th>
                                                <th>작업</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <form method="POST" action="">
                                                    <td><strong><?php echo $row['MemberID']; ?></strong></td>
                                                    <td><input type="text" name="userName" value="<?php echo htmlspecialchars($row['userName']); ?>" class="form-control form-control-sm"></td>
                                                    <td><input type="password" name="userPassword" value="<?php echo htmlspecialchars($row['userPassword']); ?>" class="form-control form-control-sm"></td>
                                                    <td><input type="text" name="Nickname" value="<?php echo htmlspecialchars($row['Nickname']); ?>" class="form-control form-control-sm"></td>
                                                    <td><input type="text" name="mobile1" value="<?php echo htmlspecialchars($row['mobile1']); ?>" class="form-control form-control-sm"></td>
                                                    <td><input type="text" name="mobile2" value="<?php echo htmlspecialchars($row['mobile2']); ?>" class="form-control form-control-sm"></td>
                                                    <td>
                                                        <div class="permission-cell">
                                                            <input type="checkbox" name="isAdmin" <?php echo $row['isAdmin'] ? 'checked' : ''; ?> class="form-check-input permission-checkbox">
                                                            <span class="<?php echo $row['isAdmin'] ? 'admin-badge' : 'user-badge'; ?>">
                                                                <?php echo $row['isAdmin'] ? '관리자' : '사용자'; ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id" value="<?php echo $row['MemberID']; ?>">
                                                        <button type="submit" name="update" class="btn btn-success btn-sm">
                                                            <i class="fas fa-save"></i> 수정
                                                        </button>
                                                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('정말 삭제하시겠습니까?');">
                                                            <i class="fas fa-trash"></i> 삭제
                                                        </button>
                                                    </td>
                                                </form>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
