<?php
session_start();
echo "こんにちは、" . htmlspecialchars($_SESSION['session_name'] ?? "ゲスト", ENT_QUOTES, 'UTF-8') . "さん";
$title = $_POST["title"];
$kinds = $_POST["kinds"]  ;
$subject = $_POST["subject"] ;
$ent_btn = $_POST["ent_btn"] ;

if (isset($ent_btn) && !$title) {
    $msg = "<font color='red'>タイトルが入力されていません</font>";
} elseif (isset($ent_btn) && !$kinds) {
    $msg = "<font color='red'>種類が選択されていません</font>";
} elseif (isset($ent_btn) && !$subject) {
    $msg = "<font color='red'>対象が選択されていません</font>";
} else {
    $msg = "";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <title>メディア登録</title>
</head>
<body>
    <br>
    えいとびっと
    <form action="media-ins.php" method="post">

        <label>タイトル:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"><br>

        <label>種類:</label>
        <input type="radio" name="kinds" value="本" <?= $kinds === '本' ? 'checked' : '' ?>> 本
        <input type="radio" name="kinds" value="動画" <?= $kinds === '動画' ? 'checked' : '' ?>> 動画<br>

        <label>対象:</label>
        <input type="radio" name="subject" value="学生" <?= $subject === '学生' ? 'checked' : '' ?>> 学生
        <input type="radio" name="subject" value="教員" <?= $subject === '教員' ? 'checked' : '' ?>> 教員<br><br>

        <input type="submit" value="登録" name="ent_btn">
        <?php
        if ($msg) {
            echo $msg;
        }
        ?>
    </form>

    <!-- トップに戻るボタン -->
    <br>
    <a href="top.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">TOPに戻る</a>

<?php
if ($msg) {
    exit;
}

// POSTされていない時は登録処理をスルー
if (!$ent_btn) {
    exit;
}

// SQL文
$sql = 'INSERT INTO media (title, kinds, subject) VALUES (:title, :kinds, :subject)';

// DBへの接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user = "user01";
$pass = "user01";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQLの実行
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':kinds', $kinds);
    $stmt->bindValue(':subject', $subject);
    $stmt->execute();

    // 結果の処理
    echo "登録しました";
} catch (PDOException $e) {
    echo "接続失敗: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "\n";
} finally {
    // DB接続を閉じる
    $pdo = null;
}
?>
<div style="display: flex; justify-content: space-between; align-items: center;">
	<div>

	<script>
	function confirm_test() {
		var select = confirm("ログアウトしますか？\n「OK」でログアウト\n");
		return select;
	}
	</script>
		<form action="logout.php" method="post" onsubmit="return confirm_test()">
			<input type="submit" value="ログアウト">
		</form>
	</div>
</div>
</body>
</html>
