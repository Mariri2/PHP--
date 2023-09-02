<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>        
    <body>
        <form action = "" method = "post">
<!--名前・コメント送信用フォーム-->
            【投稿フォーム】<br>
            <input type = "text" name = "name" placeholder = "名前"><br>
            <input type = "text" name = "comment" placeholder = "コメント"><br>
            <input type = "text" name = "password1" placeholder = "パスワード">
            <input type = "submit" name = "submit" ><br>
<!--削除番号投稿フォーム-->
            【削除フォーム】<br>
            <input type = "text" name = "delnum" placeholder = "削除番号"><br>
            <input type = "text" name = "password2" placeholder = "パスワード">
            <input type = "submit" name = "delete" value = "削除"><br>
<!--編集用投稿フォーム-->
            【編集フォーム】<br>
            <input type = "text" name = "editnum" placeholder = "編集番号"><br>
            <input type = "hidden" id = "text" name = "num" value = "<?php if(!empty($_POST["editnum"])){echo $_POST["editnum"];}?>">
            <input type = "text" name = "password3" placeholder = "パスワード">
            <input type = "submit" name = "edit" value = "編集">
    
        </form>
    </body>
</html>

<?php
//MYSQL操作を使って、mission3-5の掲示板を作成する
//①データベースを接続する（1）
    $dsn = 'mysql:dbname=tb250220db;host=localhost';
    $user = 'tb-250220';
    $password = 'mhMacpC39h';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//②データベース内にテーブルを作成する（番号・名前・コメント・日時・パスワード）（2）
    $sql = "CREATE TABLE IF NOT EXISTS table_5_1"
    ."("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
    ."name char (32),"//名前
    ."comment TEXT,"//コメント
    ."date DATETIME,"//日時
    ."pass TEXT"//パスワード
    .");";
    
    $stmt = $pdo->query($sql);//データベースに接続した後に、SQL文を実行するという意味
    echo "<hr>";//<hr>は水平線タグ   

//データの登録・書き込み
//新規投稿の場合
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password1"]) && empty($_POST["num"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date('Y-m-d H:i:s');
        $pass = $_POST["password1"];
    
        $sql = "INSERT INTO table_5_1 (name,comment,date,pass) VALUES (:name, :comment, :date, :pass)";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
        $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt -> execute();
        
    }

//削除機能の実装
        if(!empty($_POST["delnum"]) && !empty($_POST["password2"])){
//データの中からSELECT文で番号の部分とパスワードの部分のみを抽出＆合ってるかどうかも確認
        $delnum = $_POST["delnum"];
//最初に条件分岐みたいな感じで抽出してほしい部分を変数に代入しておく
        $id = $delnum;
        $pass = $_POST["password2"];
//作成したテーブルの中からidとパスワードの部分だけを抽出
        $sql = 'delete from table_5_1 where id = :id && pass = :pass';
        $stmt = $pdo -> prepare($sql);//差し替えるパラメータを含めて記述したSQLを準備
        $stmt -> bindParam(':id',$id, PDO::PARAM_INT);//差し替えるパラメータの値を指定
        $stmt -> bindParam(':pass',$pass, PDO::PARAM_INT);
        $stmt -> execute();
        }

//編集機能の実装
//入力された編集番号とパスワードが一致していたら、該当する行を表示するOK
        if(!empty($_POST["editnum"]) && !empty($_POST["password3"])){
            $editnum = $_POST["editnum"];
            
            $id = $editnum;
            $pass = $_POST["password3"];
            $sql = 'SELECT * FROM table_5_1 where id = :id && pass = :pass';
            $stmt = $pdo -> prepare($sql);//差し替えるパラメータを含めて記述したSQLを準備
            $stmt -> bindParam(':id',$id, PDO::PARAM_INT);//差し替えるパラメータの値を指定
            $stmt -> bindParam(':pass',$pass, PDO::PARAM_INT);
            $stmt -> execute();
//表示させる
            $results = $stmt -> fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo "「".$row['comment']."」".",";
                echo $row['date']."<br><br>";
            }
        }

//編集番号の再表示と投稿内容フォームの中身が存在する場合 
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password1"]) && !empty($_POST["num"])){
            $id = $_POST["num"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date('Y-m-d H:i:s');
            $pass = $_POST["password1"]; 
            
            
            $sql = 'UPDATE table_5_1 SET name = :name,comment = :comment,date = :date,pass = :pass WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':id',$id, PDO::PARAM_INT);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
            
            $stmt->execute();
        }

//データを抽出し、表示する

        $sql = 'SELECT * FROM table_5_1';
        $stmt = $pdo -> query($sql);
        $results = $stmt -> fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo "「".$row['comment']."」".",";
            echo $row['date']."<br>";
        }
?>    
