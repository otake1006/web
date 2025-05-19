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

// 検索処理
$search_name = $_GET['search_name'] ?? '';
$query = "SELECT * FROM users WHERE user_name LIKE :search_name ORDER BY login_id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['search_name' => '%' . $search_name . '%']);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー検索</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-container {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>ユーザー検索</h2>
        <div style="position: fixed; top: 10px; right: 10px;">
    <a href="login.php">
        <button type="button">ログイン画面に戻る</button>
    </a>
</div>

        <form method="get" action="">
            
            <label for="search_name">ユーザー氏名:</label>
            <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($search_name) ?>">
            <button type="submit">検索</button>
            <a href="user-ins.php"><button type="button">新規登録</button></a>

</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ログインID</th>
                    <th>ユーザー氏名</th>
                    <th>レベル</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['login_id']) ?></td>
                            <td><?= htmlspecialchars($user['user_name']) ?></td>
                            <td><?= htmlspecialchars($user['level']) ?></td>
                            <td><a href="user-upd.php?login_id=<?= $user['login_id'] ?>&user_name=<?= $user['user_name'] ?>">編集</a></td>
                            <td><a href="user-del.php?login_id=<?= $user['login_id'] ?>&user_name=<?= $user['user_name'] ?>">削除</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">検索結果がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
