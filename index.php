<?php
$dsn = 'mysql:dbname=a-team-2ch;host=localhost';
$user = 'root';
$password = '';
$id;
$threads_name;
$delete_key;
$created;
$modified;
$commentDate;

//受け取ったidパラメータを取得
$threads_id = filter_input(INPUT_GET, 'id');

//SQLでidパラメータと対応するスレッドテーブルのidを取得
if(empty($threads_id) ) {
    try{
        $dbh = new PDO($dsn, $user, $password);
        $dbh->query('SET NAMES utf8');
        $threadsDate = "select * from threads WHERE $threads_id";
        foreach ($dbh->query($threadsDate) as $row) {
            $id = $row['id'];
            $threads_name = $row['threads_name'];
            $delete_key = $row['delete_key'];
            $created = $row['created'];
            $modified = $row['modified'];
        }
        $commentDate = "select * from comments WHERE threads_id = $threads_id";
         
    }catch (PDOException $e){
        echo('Error:'.$e->getMessage());
        die();
    }     
}        
//スレッドテーブルのidと対応するコメントを取得

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body{
                background: red;
            }
            #wraper{
                width: 900px;
                margin: 0 auto;
                padding:  20px 20px;
                background: #fff;
                
            }
            h1{
                font-size: 36px;
            }
            .toukou_area{
                margin-bottom: 30px;
                border-bottom: 1px solid #ccc;
            } 
            .txt_bold{
                font-size: 18px;
                font-weight: bold;
            }
            textarea{
                width: 500px;
                height: 200px;
            }
        </style>
    </head>
    <body>
        <div id="wraper">
        <h1><?php echo $threads_name?></h1>
        
        <?php
        foreach ($dbh->query($commentDate) as $row2) {
            echo '<div class="toukou_area">';
            echo "<p>投稿者：".$row2['nickname']."&nbsp;&nbsp;ID&nbsp;".$row2['unique_id']."</p>";
            echo "<p class=\"txt_bold\">".$row2['comment']."</p>";
            echo "<p>投稿日：".$row2['created']."</p>";
            echo '</div>';
        }
        ?>
        
        <form action="comment.php" method="post">
            <dl>
                <dt>ニックネーム</dt>
                <dd><input type="text" name="name"></dd>
                <dt>コメント</dt>
                <dd><textarea name="comment"></textarea></dd>
                <dt>削除キー</dt>
                <dd><input type="text" name="delete_name" ></dd>
            </dl>
            <input type="hidden" value="<?php echo $sample?>" name="thread_id">
            <input type="submit" value="送信">
        </from>
        </div>
    </body>
</html>
