<?php

if($_SERVER[REQUEST_METHOD] == 'POST') {
	
	$title = $_POST[title];
	$URL = $_POST[URL];
	$mainbody = $_POST[mainbody];

	$err = array();
	
		if($title == '') {
			$err[title] = "記事のタイトルが入力されていません。";
		}

		if($URL == '') {
			$err[URL] = "記事のURLが入力されていません。";
		}

		if($mainbody == '') {
			$err[mainbody] = "記事の本文が入力されていません。";
		}
	
	if(empty($err)) {
		try {
			$dbh = new PDO("mysql:host=localhost; dbname=searchdb", "searchdb", "searchdb");
			$sql = "insert into search (title,URL,mainbody,created) values (:title,:URL,:mainbody,now())";
		
			$stmt = $dbh->prepare($sql);
			$params = array(":title" => $title, ":URL" => $URL, ":mainbody" => $mainbody);
			$stmt->execute($params);
		} catch (PDOException $e) {
			var_dump($e->getMessage());
		}
		
		$message = (empty($e)) ? "記事の登録が完了しました。" : "エラーが発生しました。 => $e";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>記事登録フォーム</title>
</head>

<body>
<h1>記事登録フォーム</h1>

<form action="" method="POST">
<p>タイトル <input type="text" name="title" value="<?php echo $title; ?>"> <?php echo $err[title]; ?></p>
<p>URL <input type="text" name="URL" value="<?php echo $URL; ?>"> <?php echo $err[URL]; ?></p>
<p>本文 <textarea cols="100" rows="5" name="mainbody"><?php echo $mainbody; ?></textarea> <?php echo $err[mainbody]; ?></p>
<p><input type="submit" value="送信"></p>
<div align="center"><p><?php echo $message; ?></p></div>
</form>
</body>

</html>