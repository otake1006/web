<?php
session_start();

// 管理者以外は表示しない
// セッションに権限レベルを格納したい 仮にlevelと置く
if($_SESSION['session_level']!='管理者'){
    echo '管理者権限がありません';
    exit;
}

// Initialize variables
$postMethod = $_SERVER["REQUEST_METHOD"];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $comment_id   = $_GET["comment_id"] ?? '';
    $media_number = $_GET["media_id"] ?? '';
    $comment      = $_GET["comment_text"] ?? '';
    $cmt_kinds    = $_GET["cmt_kinds"] ?? '';
    $user_name    = $_GET["user_name"] ?? '';

    $_SESSION["comment_id"] = $comment_id;
    $_SESSION["media_number"] = $media_number;
}else{
    $comment_id   = $_SESSION["comment_id"] ?? '';
    $media_number = $_SESSION["media_number"] ?? '';
    $comment      = $_POST["comment"] ?? '';
    $cmt_kinds    = $_POST["cmt_kinds"] ?? '';
    $ent_btn      = $_POST["ent_btn"] ?? '';
    $user_name    = $_SESSION['session_name'] ?? '';
}

$btn_value = ($postMethod == "POST") ? "本当に削除" : "削除";
    if ($btn_value == "本当に削除") {
    echo "本当に削除する場合は、[本当に削除]ボタンを押してください<br>";
    echo "<a href='media-res-del.php?comment_id=".$comment_id."&media_id=".$media_number."&cmt_kinds=".$cmt_kinds."&comment_text=".$comment."'>やっぱり削除しない</a>";
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>コメント削除(メディア)</title>
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <?php
            echo "こんにちは、". $user_name."(".$_SESSION['session_level'].")さん<br>";
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
// echo "<h3>以下の投稿についているコメントを削除します</h3>";

//DBへの接続
$dsn = 'mysql:host=localhost;dbname=system2024;charset=utf8';
$user="user01";
$pass="user01";

try {
    // 表示処理

    // echo "<table border=0>";
    // print("<tr><td>登録番号　</td><td>". $media_number ."</td></tr>");
    // print("<tr><td>タイトル</td>  <td>". $media_title ."</td></tr>");
    // print("<tr><td>種類</td>      <td>". $media_kinds ."</td></tr>");
    // print("<tr><td>対象</td>      <td>". $media_subject ."</td></tr>");
	// echo "</table>";

    /* ----------------------------------------------------------------------------------------------------- */
    // 登録処理


    //if($msg){exit;} //POSTされていない時は登録処理はスルー
    
    $pdo = new PDO($dsn,$user,$pass);

    $sql2 = 'SELECT * from media WHERE number = :media_number';
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindValue(':media_number', $media_number, PDO::PARAM_INT);
    $stmt2->execute();
    $result = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($postMethod == "POST" && $_POST["ent_btn"] == "本当に削除") {
        $sql = 'DELETE FROM media_comment WHERE comment_id = :comment_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<p>コメントを削除しました</p>";
    }
    
// foreachの値を変数に格納したい
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
} finally{
    // DB接続を閉じる
    $pdo = null;
}

echo "<h3>以下の投稿にコメントします</h3>";
echo "<table border=0>";
print("<tr><td>登録番号　</td><td>". $media_number ."</td></tr>");
print("<tr><td>タイトル</td>  <td>". $result['title'] ."</td></tr>");
print("<tr><td>種類</td>      <td>". $result['kinds'] ."</td></tr>");
print("<tr><td>対象</td>      <td>". $result['subject'] ."</td></tr>");
echo "</table>";
?>

    <h3>コメント入力</h3>
    <form action="media-res-del.php" method="post">
        <label>コメント番号:</label>
        <input type="text" name="comment_id" value="<?php echo $comment_id; ?>" readonly><br>

        <label>種類:</label>
        <input type="text" name="cmt_kinds" value="<?php echo $cmt_kinds;?>" readonly><br>

        <label>コメント:</label>
        <textarea name="comment" rows="5" readonly><?php echo htmlspecialchars($comment); ?></textarea><br>

        <input type="submit" value="<?php echo $btn_value; ?>" name="ent_btn"><br>
        <?php
         if($msg){echo $msg;}
        ?>
    </form>
    <?php
    print("<a href='media-dtl.php?number=".$media_number."&title=".$result['title']."&kinds=".$result['kinds']."&subject=".$result['subject']."' style='display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>メディア詳細に戻る</a>");
    ?>

</body>
</html>