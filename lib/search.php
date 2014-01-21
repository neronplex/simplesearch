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

<?php
/*
 * インクリメンタルサーチのロジック
*/
// jQuery(Ajax.html)が送信するヘッダーのチェック
if($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' && isset($_POST[ajaxkeyword])) {

	try {
		// SQL文「本文中に指定されたキーワードが入っているレコードを抜き出せ」
		$sql = "SELECT * FROM search WHERE mainbody LIKE ?";

		// クエリの送信準備
		$stmt = $dbh->prepare($sql);
		// SQL文中の?を置換方法を指定
		# ワイルドカードが付いている場合はプレースホルダーが使えない
		$params = array("%$ajaxkeyword%");
		// ここでクエリがDBに送信される
		$stmt->execute($params);
		// レコードの行数を数える
		$count = $stmt->rowCount();

		echo "{$count}件のデータがあります。<br>";
		// 検索結果を配列に入れつつ、検索件数分の回数ループ処理
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			// ヒアドキュメント
			echo <<< HERE
				<a href="$result[URL]">$result[title]</a><br />
HERE;
		}

		// 接続失敗時のエラー表示
	} catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}
?>