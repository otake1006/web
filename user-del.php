<?php
session_start();

// データベース接続情報
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

// 初期化
$error_message = "";

// フォーム処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? null;
    $password = $_POST['password'] ?? null;
    $login_id = $_SESSION['login_id'] ?? null;
    $btn = isset($_POST['btn']);

    try {
        if ($btn) {
           // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("delete from users WHERE login_id = :id");
            $stmt->bindValue(':id', $login_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "ユーザーを削除しました。";
            } else {
                echo "データが変更されませんでした。入力内容を確認してください。";
            }
        }
    } catch (PDOException $e) {
        $error_message = "エラー: " . $e->getMessage();
    }
} else {
    $user_name = $_GET['user_name'] ?? null;
    $login_id = $_GET['login_id'] ?? null;
    $_SESSION['login_id'] = $login_id;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー削除</title>
</head>
<body>
    <h1>ユーザー削除</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <form method="post" action="user-del.php">
        <label for="user_name">ユーザー氏名:</label>
        <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly><br>

        <label for="login_id">ID:</label>
        <input type="password" id="id" name="id" value="<?php echo htmlspecialchars($login_id ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly><br>

        <button type="submit" name="btn">削除</button>
    </form>
    <a href="user-list.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">ユーザー一覧に戻る</a>
</body>
</html>
