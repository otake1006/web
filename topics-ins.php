<?php
session_start();
echo "こんにちは、". $_SESSION['session_name'] ."さん<br>";
$title=$_POST["title"];
$kinds=$_POST["kinds"];
$subject=$_POST["subject"];
$ent_btn=$_POST["ent_btn"];

if(isset($ent_btn)&& !$title){
    $msg="<font color='red'>タイトルが入力されていません</font>";
}elseif(isset($ent_btn)&& !$kinds){
    $msg="<font color='red'>種類が選択されていません</font>";
}elseif(isset($ent_btn)&& !$subject){
    $msg="<font color='red'>対象が選択されていません</font>";
}
else{
    $msg="";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>トピックス登録</title>
</head>
<body>
えいとびっと
    <form action="topics-ins.php" method="post">

        <label>タイトル:</label>
        <input type="text" name="title" ><br>

        <label>種類:</label>
        <input type="radio" name="kinds" value="お知らせ" > お知らせ
        <input type="radio" name="kinds" value="ニュース"> ニュース<br>

        <label>対象:</label>
        <input type="radio" name="subject" value="学生" > 学生
        <input type="radio" name="subject" value="教員"> 教員<br><br>

        <input type="submit" value="登録" name="ent_btn">
        <?php
         if($msg){
         echo $msg;
         }
        ?>
    </form>
<?php
if($msg){exit;}
//POSTされていない時は登録処理はスルー

//押下されたボタンとエラー有無を確認

//SQL文
$sql='insert into topics(title,kinds,subject) value(:title,:kinds,:subject)';

//DBへの接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user="user01";
$pass="user01";

try {
if($ent_btn){
$pdo = new PDO($dsn,$user,$pass);
//SQLの実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':kinds', $kinds);
$stmt->bindValue(':subject', $subject);
$stmt->execute();
//結果の処理
echo "登録しました";
}
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