<?php
session_start();

// 管理者以外は表示しない
// セッションに権限レベルを格納したい 仮にlevelと置く
if($_SESSION['session_level']!='管理者'){
    echo '管理者権限がありません';
    exit;
}

// 表示で使う変数

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $topics_number  = $_GET["number"] ?? '';
    $topics_title   = $_GET["title"] ?? '';
    $topics_kinds   = $_GET["kinds"] ?? '';
    $topics_subject = $_GET["subject"] ?? '';
    $_SESSION["topics_number"] = $topics_number;
    $_SESSION["topics_title"] = $topics_title;
    $_SESSION["topics_kinds"] = $topics_kinds;
    $_SESSION["topics_subject"] = $topics_subject;
}else{
    $topics_number = $_SESSION["topics_number"] ?? '';
    $topics_title = $_SESSION["topics_title"] ?? '';
    $topics_kinds = $_SESSION["topics_kinds"] ?? '';
    $topics_subject = $_SESSION["topics_subject"] ?? '';
    $cmt_kinds = $_POST["cmt_kinds"] ?? '';
    $comment   = $_POST["comment"] ?? '';
    $ent_btn   = $_POST["ent_btn"] ?? '';
    $user_name = $_SESSION['session_name'] ?? '';
}


?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>コメント登録(topic)</title>
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <?php
            echo "こんにちは、". $_SESSION['session_name']."(".$_SESSION['session_level'].")さん<br>";
		    echo "えいとビット";
            ?>
        </div>
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
    echo "<h3>以下の投稿にコメントします</h3>";
    echo "<table border=0>";
    print("<tr><td>登録番号　</td><td>". $topics_number ."</td></tr>");
    print("<tr><td>タイトル</td>  <td>". $topics_title ."</td></tr>");
    print("<tr><td>種類</td>      <td>". $topics_kinds ."</td></tr>");
    print("<tr><td>対象</td>      <td>". $topics_subject ."</td></tr>");
	echo "</table>";
    
    ?>

    <h3>コメント入力</h3>
    <form action="topics-res-ins.php" method="post">
        <label>種類:</label>
        <input type="radio" name="cmt_kinds" value="感想" > 感想 
        <input type="radio" name="cmt_kinds" value="質問"> 質問<br>

        <label>コメント:</label>
        <textarea name="comment" rows="5"></textarea><br>

        <input type="submit" value="登録" name="ent_btn"><br>
        <?php
         if($msg){echo $msg;}
        ?>
    </form>
    <a href="top.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">TOPに戻る</a>

<?php

// 登録で使う変数

// 入力確認
if(isset($ent_btn) && !$cmt_kinds){
    $msg="<font color='red'>種類が選択されていません</font>";
}elseif(isset($ent_btn) && !$comment){
    $msg="<font color='red'>コメントが入力されていません</font>";
}
else{
    $msg="";
}

//DBへの接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user="user01";
$pass="user01";

try {

    // 登録処理

    //if($msg){exit;} //POSTされていない時は登録処理はスルー
       

    $pdo = new PDO($dsn,$user,$pass);
    if ($msg === '' && $ent_btn) {
        $sql = 'INSERT INTO topics_comment (topics_id, cmt_kinds, comment_text, user_name) VALUES (:topics_id, :cmt_kinds, :comment_text, :user_name)';
        //echo 'SQL確認：'.$sql;
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':topics_id', $topics_number, PDO::PARAM_STR);
        $stmt->bindValue(':cmt_kinds', $cmt_kinds, PDO::PARAM_STR);
        $stmt->bindValue(':comment_text', $comment, PDO::PARAM_STR); 
        $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR); 
        $stmt->execute();
        
        echo "<p>コメントを登録しました！</p>";

        // echo $media_number.":media<br>";
        // echo $cmt_kinds."<br>";
        // echo $comment."<br>";
        // echo $user_name."<br>";
    }
    
    
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