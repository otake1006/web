
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>画面名称</title>
</head>
<body>
<h1>メディア登録</h1>
    <form action="media-ins.php" method="post">
        <label>登録番号:</label>
        <input type="text" name="reg_number" required><br>

        <label>タイトル:</label>
        <input type="text" name="title" required><br>

        <label>種類:</label>
        <input type="radio" name="type" value="本" required> 本
        <input type="radio" name="type" value="動画"> 動画

        <label>対象:</label>
        <input type="radio" name="target" value="学生" required> 学生
        <input type="radio" name="target" value="教員"> 教員<br><br>

        <input type="submit" value="登録">
    </form>
    <h2>データの更新</h2>
    <form action="media-upd.php" method="post">
        <label>登録番号（更新用）:</label>
        <input type="text" name="reg_number" required><br>
        <label>新しいタイトル:</label>
        <input type="text" name="title" required><br>
        <input type="submit" value="更新">
    </form>

    <h2>データの削除</h2>
    <form action="media-del.php" method="post">
        <label>登録番号（削除用）:</label>
        <input type="text" name="reg_number" required><br>
        <input type="submit" value="削除">
    </form>




</body>
</html>
