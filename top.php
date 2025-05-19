<?php
//セッション開始
session_start();
//echo "こんにちは、". $_SESSION['session_name'] ."さん";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>トップ画面</title>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center;">
	<div>
		<?php
		echo "こんにちは、". $_SESSION['session_name'] ."さん<br>";
		echo "えいとビット";
		?>
	</div>
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

<?php

//DBへの接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user="user01";
$pass="user01";

//$name=$_SESSION['session_name'];	//ユーザ名：ログイン画面からとってくる

try {
    $pdo = new PDO($dsn,$user,$pass);

/* ----------------------------------------------------------------------------------------------------- */
	//トピック
	
	echo "<h2>トピックス　<a href='http://10.123.100.118/topics-ins.php' target='_blank'>トピックス新規登録</a></h2>";

	//SQLの実行(topic)
    $sql='SELECT t.number,t.title,t.kinds,t.subject,COUNT(c.comment_id) as cnt
			FROM `topics` t 
			LEFT JOIN topics_comment c on t.number = c.topics_id
			GROUP BY t.number
			ORDER BY t.number desc LIMIT 5';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	// テーブル
	echo "<table border=1>";
	print("<tr>");
	echo("<th>Number</th><th>種類</th><th>対象</th><th>タイトル</th><th>コメント件数</th><th>編集</th><th>削除</th>");
		
	while($result = $stmt-> fetch(PDO::FETCH_ASSOC)){
		print("<tr>");
		print("<td>" . $result['number'] ."</td>");
		print("<td>" . $result['kinds'] . "</td>");	// 種類、
		print("<td>" . $result['subject'] . "</td>");// 値を渡す↓ &〇〇=を追加する
		print("<td><a href='http://10.123.100.118/topics-dtl.php?number=".$result['number']."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' target='_blank'>" . $result['title'] . "</a></td>");
		print("<td>" . $result['cnt'] ."</td>");
		print("<td><a href='http://10.123.100.118/topics-upd.php?number=".$result['number']."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' target='_blank'>編集</a></td>");
		print("<td><a href='http://10.123.100.118/topics-del.php?number=".$result['number']."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' target='_blank'>削除</a></td>");
        print("</tr>");
    }
	echo "</table>";
	
    
/* ----------------------------------------------------------------------------------------------------- */
	//メディア
	echo "<h2>メディア　<a href='http://10.123.100.118/media-ins.php' target='_blank'>メディア新規登録</a></h2>";

    $sql='SELECT m.number,m.title,m.kinds,m.subject,COUNT(c.comment_id) as cnt
			FROM `media` m 
			LEFT JOIN media_comment c on m.number = c.media_id
			GROUP BY m.number
			ORDER BY m.number desc LIMIT 5';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
    
	// テーブル
	echo "<table border=1>";
	print("<tr>");
	echo("<th>Number</th><th>種類</th><th>対象</th><th>タイトル</th><th>コメント件数</th><th>編集</th><th>削除</th>");
	
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print("<tr>");
		print("<td>" . $result['number'] ."</td>");
		print("<td>" . $result['kinds'] . "</td>");	// 種類、
		print("<td>" . $result['subject'] . "</td>");// 値を渡す↓ &〇〇=を追加する
		print("<td><a href='http://10.123.100.118/media-dtl.php?number=".$result['number']."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' target='_blank'>" . $result['title'] . "</a></td>");
		print("<td>" . $result['cnt'] ."</td>");
		print("<td><a href='http://10.123.100.118/media-upd.php?number=".$result['number']."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' target='_blank'>編集</a></td>");
		print("<td><a href='http://10.123.100.118/media-del.php?number=".$result['number']."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' target='_blank'>削除</a></td>");
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

</body>
</html>
