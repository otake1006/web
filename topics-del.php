<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <title>トピックス削除</title>
</head>
<body>
<?php
session_start();
echo "こんにちは、" . $_SESSION['session_name'] . "さん<br>";

// Initialize variables
$postMethod = $_SERVER["REQUEST_METHOD"];
$msg = "";

// Determine request method
if ($postMethod == "POST") {
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

// Determine button value
$btn_value = ($postMethod == "POST") ? "本当に削除" : "削除";
if ($btn_value == "本当に削除") {
    echo "本当に削除する場合は、[本当に削除]ボタンを押してください<br>";
    echo "<a href='top.php'>やっぱり削除しない</a>";
}
?>

えいとびっと
<form action="topics-del.php" method="post">
    <label>登録番号:</label>
    <input type="text" name="number" value="<?php echo $number; ?>" readonly><br>

    <label>タイトル:</label>
    <input type="text" name="title" value="<?php echo $title; ?>"readonly><br>

    <label>種類:</label>
    <input type="text" name="kinds" value="<?php echo $kinds;?>" readonly><br>


    <label>対象:</label>
    <input type="text" name="subject" value="<?php echo $subject; ?>" readonly><br>


    <input type="submit" value="<?php echo $btn_value; ?>" name="ent_btn">
</form>
<br>
<a href="top.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">TOPに戻る</a>

<?php
if ($msg) {
    exit;
}


// Handle deletion if POST method and confirmed
if ($postMethod == "POST" && $_POST["ent_btn"] == "本当に削除") {
    
    // Prepare SQL statement
    $sql = 'DELETE FROM topics WHERE number = :number';

    // Database connection
    $dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
    $user = "user01";
    $pass = "user01";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Execute SQL
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':number', $number, PDO::PARAM_STR);
        $stmt->execute();

        echo "削除しました";
    } catch (PDOException $e) {
        echo "接続失敗: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } finally {
        // Close the connection
        $pdo = null;
    }
}
?>
</body>
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
</html>
