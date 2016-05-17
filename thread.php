<?php
$dsn = 'mysql:dbname=a-team-2ch;host=localhost'; // charcter:utf8;
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
if(!empty($threads_id) ) {
    try{
        $pdo = new PDO($dsn, $user, $password);
        $pdo->query('SET NAMES utf8'); // NG
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアを使えるようにする
       
        $threadsDate = $pdo->prepare("select * from threads WHERE :threadsIdDate");
        $threadsDate->bindValue(':threadsIdDate',$threads_id);
        
        $commentpdo = new PDO($dsn, $user, $password);
        $commentpdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアを使えるようにする
        $commentDate = $commentpdo->prepare("select * from comments WHERE threads_id = :threadsIdDate");
        $commentDate ->bindValue(':threadsIdDate',$threads_id);
        $threadsDate->execute();//変更を確定
        $commentDate->execute();//変更を確定
       
           
        $threads = $threadsDate->fetchAll();
        
            foreach ($threads as $row) {
                $id = $row['id'];
                $threads_name = $row['threads_name'];
                $delete_key = $row['delete_key'];
                $created = $row['created'];
                $modified = $row['modified'];
            }  
        
         
    }catch (PDOException $e){
        echo('Error:'.$e->getMessage());
        die();
    }     
}        
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
                border-bottom: 1px solid #ccc;
            } 
            .txt_bold{
                font-size: 20px;
                font-weight: bold;
                margin-top: 0;
            }
            
            .toukousya{
                font-size: 12px;
                margin-bottom: 7px;
            }
            
            .toukoubi {
                margin-bottom: 5px;
                text-align: right;
                font-size: 12px;
            }
            textarea{
                width: 500px;
                height: 200px;
            }
            
            dl{
                width: 500px;
                margin: 0 auto;
            }
            
            dd {
                margin-left: 0px;
                margin-bottom: 15px;
            }
            
            dt {
                margin-bottom: 10px;
            }
            
            [name="Submit"]{
                display: block;
                width: 90px;
                height: 40px;
                margin: 0 auto;
            }
            
            form{
                padding-top: 30px;
            }
            
           
        </style>
    </head>
    <body>
        <div id="wraper">
        <h1><?php echo $threads_name; ?></h1>
        
        <?php
            foreach ($commentDate->fetchAll() as $row2) {
            echo '<div class="toukou_area">';
            echo "<p class=\"toukousya\">投稿者：".$row2['nickname']."&nbsp;&nbsp;ID&nbsp;".$row2['unique_id']."</p>";
            echo "<p class=\"txt_bold\">".$row2['comment']."</p>";
            echo "<p class=\"toukoubi\">投稿日：".$row2['created']."</p>";
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
            <input type="hidden" value="<?php echo $threads_id ?>" name="threads_id">
            <input type="submit" value="投稿" name="Submit">
        </from>
        </div>
    </body>
</html>
