<?php
session_start();
$title=$_POST["title"];
$kinds=$_POST["kinds"];
$subject=$_POST["subject"];
$ent_btn=$_POST["ent_btn"];

if(isset($ent_btn)&& !$title){
    $msg="<font color='red'>氏名が入力されていません</font>";
}else{
    $msg="";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>画面名称</title>
</head>
<body>
<?php
   print('こんにちは、'.$X.'さん');
?>
えいとびっと
    <form action="sample.php" method="post">

        <label>タイトル:</label>
        <input type="text" name="title" required><br>

        <label>種類:</label>
        <input type="radio" name="kinds" value="お知らせ" required> お知らせ
        <input type="radio" name="kinds" value="ニュース"> ニュース<br>

        <label>対象:</label>
        <input type="radio" name="subject" value="学生" required> 学生
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
$sql='insert into media(title,kinds,subject,comment) value(:title,:kinds,:subject,:comment)';

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
$stmt->bindValue(':comment', "");
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

   




</body>
</html>