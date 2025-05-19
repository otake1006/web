<?php
session_start();

// データベース接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user = 'user01';
$pass = 'user01';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

// 初期値
$login_id = '';

// POSTデータの取得
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = $_POST['login_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_name = $_POST['user_name'] ?? '';

    // SQL実行
    $sql = "SELECT * FROM users WHERE login_id = :login_id AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch();

    if ($user) {
        // ログイン成功時にセッション変数にユーザー名,権限レベルを保存
        $_SESSION['session_name'] = $user['user_name'];
        $_SESSION['session_level'] = $user['level'];
        header("Location: top.php");
        exit;
    } else {
        // 認証失敗メッセージ
        echo "<script>alert('認証失敗: ログインIDまたはパスワードが違います');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 350px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .login-container label {
            display: block;
            font-size: 14px;
            color: #555555;
            margin-bottom: 5px;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container a button {
            margin-top: 10px;
            background-color: #6c757d;
        }

        .login-container a button:hover {
            background-color: #5a6268;
        }

        .login-container a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ログイン</h2>
        <form action="login.php" method="POST">
            <label for="login_id">ログインID:</label>
            <input type="text" id="login_id" name="login_id" value="<?= htmlspecialchars($login_id, ENT_QUOTES, 'UTF-8') ?>" required>
            
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">ログイン</button>
            <a href="user-list.php">
                <button type="button">ユーザ一覧</button>
            </a>
        </form>
    </div>
</body>
</html>
