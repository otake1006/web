
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

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? null;
    $password = $_POST['password'] ?? null;
    $level = $_POST['level'] ?? null;

    // 入力値のバリデーション
    if (empty($user_name) || empty($password) || empty($level)) {
        die("すべてのフィールドを入力してください。");
    }

    try {
        // データベースに新規ユーザーを挿入
        $stmt = $pdo->prepare("INSERT INTO users (user_name, password, level) VALUES (?, ?, ?)");
        $stmt->execute([$user_name, $password, $level]);

        echo "新しいユーザーが登録されました。";
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ユーザー登録</title>
</head>
<body>
    <form method="post" action="">
        <label for="user_name">ユーザー氏名:</label>
        <input type="text" id="user_name" name="user_name" required><br>

        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="level">レベル:</label>
        <select id="level" name="level" required>
            <option value="管理者">管理者</option>
            <option value="一般">一般</option>
        </select><br>

        <button type="submit">登録</button>
    </form>
    <a href="user-list.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">ユーザー一覧に戻る</a>

</body>
</html>
