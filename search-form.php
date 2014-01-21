<?php
/*
 * 共通で使う変数などの定義
 */
// キーワードの受け渡しに使う変数
$ajaxkeyword = $_POST[ajaxkeyword];
$formkeyword = $_POST[formkeyword];
// PDOでDBに接続開始
$dbh = new PDO("mysql:host=localhost; dbname=searchdb", "searchdb", "searchdb");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>検索</title>
</head>
<body>
<h1>検索</h1>
<form method="post">
<input type="text" name="formkeyword" value="<?php echo $formkeyword; ?>">
<input type="submit" value="アメーバで検索検索！">
</form>

<?php
/*
 * 通常検索のロジック
 */
// 検索フォームから送信されるヘッダーのチェック
if($_SERVER[REQUEST_METHOD] == 'POST' && isset($_POST[formkeyword])) {
	
	try {
		$sql = "SELECT * FROM search WHERE mainbody LIKE ?";
		
		$stmt = $dbh->prepare($sql);
		$params = array("%$formkeyword%");
		$stmt->execute($params);
		$count = $stmt->rowCount();
		
		echo "{$count}件のデータがあります。<br />";
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo <<< HERE
				<a href="$result[URL]">$result[title]</a><br />
HERE;
		}
		
	} catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}
?>

</body>
</html>