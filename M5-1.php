<?php



// データベースに接続するための情報を変数に代入
$db_host = ""; // データベースサーバーのホスト名
$db_user = ""; // データベースのユーザー名
$db_pass = ""; // データベースのパスワード
$db_name = ""; // データベースの名前

 
 // データベースに接続する
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);  




// 投稿テーブルを作成するSQL文を変数に代入
$sql_create_table = "CREATE TABLE IF NOT EXISTS posts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)";

// 投稿テーブルを作成する
mysqli_query($conn, $sql_create_table);

// 投稿フォームから送信されたデータがある場合は、データベースに保存する
if (isset($_POST["submit"])) {
    // 入力された名前とメッセージを変数に代入
    $name = $_POST["name"];
    $message = $_POST["message"];

    // 名前とメッセージが空でない場合は、データベースに保存するSQL文を変数に代入
    if ($name != "" && $message != "") {
        $sql_insert = "INSERT INTO posts (name, message) VALUES ('$name', '$message')";
    }

    // データベースに保存する
    mysqli_query($conn, $sql_insert);
}

// 削除フォームから送信されたデータがある場合は、データベースから削除する
if (isset($_POST["delete"])) {
    // 削除する投稿のIDを変数に代入
    $id = $_POST["id"];

    // IDが空でない場合は、データベースから削除するSQL文を変数に代入
    if ($id != "") {
        $sql_delete = "DELETE FROM posts WHERE id = '$id'";
    }

    // データベースから削除する
    mysqli_query($conn, $sql_delete);
}

// 編集フォームから送信されたデータがある場合は、データベースを更新する
if (isset($_POST["edit"])) {
    // 編集する投稿のIDと新しいメッセージを変数に代入
    $id = $_POST["id"];
    $message = $_POST["message"];

    // IDとメッセージが空でない場合は、データベースを更新するSQL文を変数に代入
    if ($id != "" && $message != "") {
        $sql_update = "UPDATE posts SET message = '$message' WHERE id = '$id'";
    }

    // データベースを更新する
    mysqli_query($conn, $sql_update);
}

// データベースから全ての投稿を取得するSQL文を変数に代入
$sql_select_all = "SELECT * FROM posts ORDER BY id DESC";

// データベースから全ての投稿を取得する
$result = mysqli_query($conn, $sql_select_all);

// データベースから取得した投稿を配列に格納する
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

// データベースの接続を閉じる
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    
    <h3>投稿フォーム</h3>
    <form action="" method="post">
        <p>名前: <input type="text" name="name" required></p>
        <p>メッセージ: <textarea name="message" rows="2" cols="40" required></textarea></p>
        <p><input type="submit" name="submit" value="投稿"></p>
    
   
   
   
   </form>
    <h3>投稿</h3>
    <?php if (count($posts) > 0): // 投稿がある場合は表示する ?>
        <ul>
            <?php foreach ($posts as $post): // 投稿の数だけ繰り返す ?>
                <li>
                    <p>投稿番号: <?php echo $post["id"]; ?></p>
                    <p>名前: <?php echo $post["name"]; ?></p>
                    <p>メッセージ: <?php echo $post["message"]; ?></p>
                    <p>作成日時: <?php echo $post["created_at"]; ?></p>
                    <p>更新日時: <?php echo $post["updated_at"]; ?></p>
                    <!-- 削除フォーム -->
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">
                        <input type="submit" name="delete" value="削除">
                    </form>
                   
                    <!-- 編集フォーム -->
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">
                        <input type="text" name="message" value="<?php echo $post["message"]; ?>">
                         <input type="submit" name="edit" value="編集">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
   
    <?php endif; ?>
</body>
</html>

