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
    $comment_id   = $_GET["cmt_id"] ?? '';
    $topics_number = $_GET["topics_id"] ?? '';
    $comment      = $_GET["comment_text"] ?? '';
    $cmt_kinds    = $_GET["cmt_kinds"] ?? '';
    $user_name    = $_GET["user_name"] ?? '';

    $_SESSION["cmt_id"] = $comment_id;
    $_SESSION["media_number"] = $media_number;
}else{
    $comment_id   = $_SESSION["cmt_id"] ?? '';
    $topics_number = $_SESSION["topics_number"] ?? '';
    $comment      = $_POST["comment"] ?? '';
    $cmt_kinds    = $_POST["cmt_kinds"] ?? '';
    $ent_btn      = $_POST["ent_btn"] ?? '';
    $user_name    = $_SESSION['session_name'] ?? '';
}

$btn_value = ($postMethod == "POST") ? "本当に削除" : "削除";
    if ($btn_value == "本当に削除") {
    echo "本当に削除する場合は、[本当に削除]ボタンを押してください<br>";
    echo "<a href='topics-res-del.php?comment_id=".$comment_id."&topics_id=".$topics_number."&cmt_kinds=".$cmt_kinds."&comment_text=".$comment."'>やっぱり削除しない</a>";
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>コメント削除(topics)</title>
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
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'SELECT * FROM topics WHERE number = :topics_number'; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':topics_number', $topics_number, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h3>以下の投稿についているコメントを削除します</h3>";
    echo "<table border=0>";
    echo "<tr><td>登録番号　</td><td><input type='text' value='" . htmlspecialchars($topics_number, ENT_QUOTES, 'UTF-8') . "' readonly></td></tr>";
    echo "<tr><td>タイトル</td><td><input type='text' value='" . htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') . "' readonly></td></tr>";
    echo "<tr><td>種類</td><td><input type='text' value='" . htmlspecialchars($result['kinds'], ENT_QUOTES, 'UTF-8') . "' readonly></td></tr>";
    echo "<tr><td>対象</td><td><input type='text' value='" . htmlspecialchars($result['subject'], ENT_QUOTES, 'UTF-8') . "' readonly></td></tr>";
    echo "</table>";

    if ($postMethod == "POST" && $_POST["ent_btn"] == "本当に削除") {
        $sql = 'DELETE FROM topics_comment WHERE comment_id = :comment_id';
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
?>

    <h3>コメント入力</h3>
    <form action="topics-res-del.php" method="post">
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
    <a href="top.php" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">TOPに戻る</a>


</body>
</html>