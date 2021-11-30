<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <body>

<?php
    //データベースに接続
    $dsn = 'データベース名';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    $name=filter_input(INPUT_POST,'name');
    $comment=filter_input(INPUT_POST,'comment');
    $delnum=filter_input(INPUT_POST,'delnum');
    $editnum=filter_input(INPUT_POST,'editnum');
    $henshunum=filter_input(INPUT_POST,'henshunum');
    
    $editname="";
    $editstr="";
    
    //編集行取り出し//
    if(isset($_POST["edit"]) && (!empty($editnum))){
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
             foreach ($results as $row){
                 if ($row['id'] == $editnum){
                    $editname=$row['name'];
                    $editstr=$row['comment'];
                    break; 
                 }
             }
    }

    //投稿編集
    if(isset($_POST["sub"]) && (!empty($name) && !empty($comment) && !empty($henshunum))){
        
        $id = $henshunum; //変更する投稿番号
        $sql = 'UPDATE mission5 SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
        
        
    // 新規フォームの書き込み
    if(isset($_POST["sub"]) && (!empty($name && $comment)) && (empty($henshunum))){
        
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment) VALUES (:name, :comment)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> execute();
    }
    
    // 削除フォーム
    if(isset($_POST["delete"]) && (!empty($delnum))){
    $id = $delnum;
    $sql = 'delete from mission5 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }
    
    
?>   

    <form action="" method="post">
        <h2>【送信フォーム】</h2>
        氏　　名:<input type="text" name="name" autocomplete="off" value="<?php echo $editname;?>"><br>
        コメント:<input type="text" name="comment"  autocomplete="off" value="<?php echo $editstr;?>"><br>
        <input type="submit" name="sub"><br>
        <h2>【削除フォーム】</h2>
        対象番号:<input type="number" name="delnum" autocomplete="off"><br>
        <input type="submit" name="delete" value="削除">
        <h2>【編集フォーム】</h2><br>
        編集番号:<input type="number" name="editnum" autocomplete="off"><br>
        <input type="submit" name="edit" value="編集"><br>
        
        <input type="hidden" name="henshunum" value="<?php 
        echo $editnum;?>"><br>
    </form>
    
    <h2>--投稿一覧--</h2>
     <?php
        //表示
        #テーブルの中身を表示#
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].'<br>';
                echo "<hr>";
            }
        ?>
</body>
</html>