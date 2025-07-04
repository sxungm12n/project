<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// 로그인 여부 확인
if (!isset($_SESSION['username'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login/login.php';</script>";
    exit;
}

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('세션에 userID가 설정되지 않았습니다.'); location.href='login/login.php';</script>";
    exit;
}

$userID = $_SESSION['userID'];

// 데이터베이스 연결
$conn = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postID']) && isset($_POST['title']) && isset($_POST['content'])) {
    $postID = $_POST['postID'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // 게시글 업데이트
    $stmt = $conn->prepare("UPDATE postsTBL SET title=?, content=? WHERE postID=? AND userID=?");
    $stmt->bind_param("ssii", $title, $content, $postID, $userID);
    $stmt->execute();
    $stmt->close();
    header("Location: my_posts.php");
    exit;
}

// 게시글 ID 확인
if (isset($_GET['postID'])) {
    $postID = $_GET['postID'];
    // 해당 사용자가 작성한 게시글인지 확인
    $stmt = $conn->prepare("SELECT * FROM postsTBL WHERE postID=? AND userID=?");
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $content = $row['content'];
    } else {
        // 해당 사용자가 작성한 게시글이 아닌 경우
        echo "<script>alert('해당 게시글에 대한 권한이 없습니다.'); location.href='my_posts.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('잘못된 요청입니다.'); location.href='my_posts.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 수정</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            transition: all 0.3s ease-in-out;
        }

        .container:hover {
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
        }

        h1 {
            margin-top: 0;
            font-size: 28px;
            text-align: center;
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        textarea {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease-in-out;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        textarea {
            height: 200px;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        .back-button {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #5cb85c;
            font-size: 16px;
            border: 1px solid #5cb85c;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-button:hover {
            background-color: #5cb85c;
            color: white;
        }

        .back-button i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>글 수정</h1>

        <!-- 게시글 수정 폼 -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="postID" value="<?php echo $postID; ?>">
            <label for="title">제목:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

            <label for="content">내용:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>

            <input type="submit" value="수정">
        </form>

        <a href="my_posts.php" class="back-button"><i class="fas fa-arrow-left"></i> 취소</a>
    </div>
</body>
</html>