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
		// SQL文「指定されたキーワードが入っているレコードのカラムを抜き出せ」
		$sql = "SELECT title,URL FROM search WHERE mainbody LIKE ?";

		// クエリの送信準備
		$stmt = $dbh->prepare($sql);
		// SQL文中の?を置換方法を指定
		# ワイルドカードが付いている場合はプレースホルダーが使えない
		$params = array("%$ajaxkeyword%");
		// ここでクエリがDBに送信される
		$stmt->execute($params);
		// レコードの行数を数える
		$count = $stmt->rowCount();
		
		// 検索結果が0件の場合
		if($count == "0") {
			# 結果に検索0件とだけ入れて返す
			$result[count] = $count;
		// レコードがあった場合
		}else {
			# 件数分だけループ処理
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				# 件数とレコードを連想配列に入れて返す
				$result[count] = $count;
				$result[] = $row;
			}
		}
			
		// JSON出力
		$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT;
		header('Content-type: application/json');
		echo json_encode($result, $options);

	// 接続失敗時のエラー処理
	} catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}
?>