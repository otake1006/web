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
    $media_number  = $_GET["number"] ?? '';
    $media_title   = $_GET["title"] ?? '';
    $media_kinds   = $_GET["kinds"] ?? '';
    $media_subject = $_GET["subject"] ?? '';
    $_SESSION["media_number"] = $media_number;
    $_SESSION["media_title"] = $media_title;
    $_SESSION["media_kinds"] = $media_kinds;
    $_SESSION["media_subject"] = $media_subject;
}else{
    $media_number = $_SESSION["media_number"] ?? '';
    $media_title = $_SESSION["media_title"] ?? '';
    $media_kinds = $_SESSION["media_kinds"] ?? '';
    $media_subject = $_SESSION["media_subject"] ?? '';
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
<title>コメント登録(メディア)</title>
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <?php
            echo "こんにちは、". $_SESSION['session_name']."(".$_SESSION['session_level'].")さん<br>";
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
    echo "<h3>以下の投稿にコメントします</h3>";
    echo "<table border=0>";
    print("<tr><td>登録番号　</td><td>". $media_number ."</td></tr>");
    print("<tr><td>タイトル</td>  <td>". $media_title ."</td></tr>");
    print("<tr><td>種類</td>      <td>". $media_kinds ."</td></tr>");
    print("<tr><td>対象</td>      <td>". $media_subject ."</td></tr>");
	echo "</table>";
    
    ?>

    <h3>コメント入力</h3>
    <form action="media-res-ins.php" method="post">
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
    <?php
    print("<a href='media-dtl.php?number=".$media_number."&title=".$media_title."&kinds=".$media_kinds."&subject=".$media_subject."' style='display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>メディア詳細に戻る</a>");
    ?>
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
        $sql = 'INSERT INTO media_comment (media_id, cmt_kinds, comment_text, user_name) VALUES (:media_id, :cmt_kinds, :comment_text, :user_name)';
        //echo 'SQL確認：'.$sql;
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':media_id', $media_number, PDO::PARAM_STR);
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