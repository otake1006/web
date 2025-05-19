<?php
session_start(); // セッションを開始
session_unset(); // すべてのセッション変数を削除
session_destroy(); // セッションを完全に破棄
header("Location: login.php"); // ログイン画面へリダイレクト（必要に応じて変更）
exit();
?>