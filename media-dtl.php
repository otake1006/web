<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <title>メディア詳細</title>
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

?>

えいとびっと
<form action="media-del.php" method="post">
    <label>登録番号:</label>
    <input type="text" name="number" value="<?php echo $number; ?>" readonly><br>

    <label>タイトル:</label>
    <input type="text" name="title" value="<?php echo $title; ?>"readonly><br>

    <label>種類:</label>
    <input type="text" name="kinds" value="<?php echo $kinds;?>" readonly><br>


    <label>対象:</label>
    <input type="text" name="subject" value="<?php echo $subject; ?>" readonly>　　　　　
    <?php print("<td><a href='http://10.123.100.118/media-res-ins.php?number=".$number."&title=".$title."&kinds=".$kinds."&subject=".$subject."' target='_blank'>コメント新規登録</a></td>");?>


</form>

<?php

//DBへの接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user="user01";
$pass="user01";


try {
    $pdo = new PDO($dsn,$user,$pass);


    $sql='select * from media_comment where media_id = :number';
	$stmt = $pdo->prepare($sql);
    $stmt->bindValue(':number', $number, PDO::PARAM_STR);
	$stmt->execute();


    

	// テーブル
	echo "<table border=1>";
	print("<tr>");
	echo("<th>名前</th><th>種類</th><th>コメント</th><th>編集</th><th>削除</th>");
		
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print("<tr>");
		print("<td>" . $result['user_name'] ."</td>");
		print("<td>" . $result['cmt_kinds'] . "</td>");	// 種類、
		echo nl2br("<td>" . $result['comment_text'] . "</td>");// 値を渡す↓ &〇〇=を追加する
		print("<td><a href='http://10.123.100.118/media-res-upd.php?comment_id=".$result['comment_id']."&media_id=".$result['media_id']."&cmt_kinds=".$result['cmt_kinds']."&comment_text=".$result['comment_text']."&uaer_name".$result['user_name']."' target='_blank'>編集</a></td>");
		print("<td><a href='http://10.123.100.118/media-res-del.php?comment_id=".$result['comment_id']."&media_id=".$result['media_id']."&cmt_kinds=".$result['cmt_kinds']."&comment_text=".$result['comment_text']."&uaer_name".$result['user_name']."' target='_blank'>削除</a></td>");
        print("</tr>");
    }
	echo "</table>";
    // foreachの値を変数に格納したい
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
} finally{
    // DB接続を閉じる
    $pdo = null;
}
    ?>
<a href="top.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">TOPに戻る</a>
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
