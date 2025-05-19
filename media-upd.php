<?php
session_start();
echo "こんにちは、" . htmlspecialchars($_SESSION['session_name'] ?? "ゲスト", ENT_QUOTES, 'UTF-8') . "さん";

// Initialize variables
$post = $_SERVER["REQUEST_METHOD"];
$msg = "";
$number = $title = $kinds = $subject = "";

// Request handling
if ($post == "POST") {
    $number = $_POST['number'] ?? '';
    $title = $_POST['title'] ?? '';
    $kinds = $_POST['kinds'] ?? '';
    $subject = $_POST['subject'] ?? '';
} else {
    $number = $_GET['number'] ?? '';
    $title = $_GET['title'] ?? '';
    $kinds = $_GET['kinds'] ?? '';
    $subject = $_GET['subject'] ?? '';
}

// Validation
if (isset($_POST["ent_btn"])) {
    if (!$title) {
        $msg = "<font color='red'>タイトルが入力されていません</font>";
    } elseif (!$kinds) {
        $msg = "<font color='red'>種類が選択されていません</font>";
    } elseif (!$subject) {
        $msg = "<font color='red'>対象が選択されていません</font>";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <title>メディア登録</title>
</head>
<body>
<br>えいとびっと
<form action="" method="post">
    <label>登録番号:</label>
    <input type="text" name="number" value="<?php echo $number; ?>" readonly><br>

    <label>タイトル:</label>
    <input type="text" name="title" value="<?php echo $title; ?>"><br>

    <label>種類:</label>
    <input type="radio" name="kinds" value="本" <?php echo $kinds === "本" ? "checked" : ""; ?>> 本
    <input type="radio" name="kinds" value="動画" <?php echo $kinds === "動画" ? "checked" : ""; ?>> 動画<br>

    <label>対象:</label>
    <input type="radio" name="subject" value="学生" <?php echo $subject === "学生" ? "checked" : ""; ?>> 学生
    <input type="radio" name="subject" value="教員" <?php echo $subject === "教員" ? "checked" : ""; ?>> 教員<br><br>

    <input type="submit" value="更新" name="ent_btn">
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
// If validation error exists, skip the update process
if ($msg) {
    exit;
}

// If POST method and form submitted
if ($post == "POST" && isset($_POST["ent_btn"])) {
    // SQL Query
    $sql = 'UPDATE media SET title = :title, kinds = :kinds, subject = :subject WHERE number = :number';

    // Database connection
    $dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
    $user = "user01";
    $pass = "user01";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Execute SQL
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':kinds', $kinds, PDO::PARAM_STR);
        $stmt->bindValue(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindValue(':number', $number, PDO::PARAM_STR);
        $stmt->execute();

        echo "更新しました";
    } catch (PDOException $e) {
        echo "接続失敗: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } finally {
        // Close the connection
        $pdo = null;
    }
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
