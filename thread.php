<?php
require('./_util/DbUtil.php');
$id;
$threadsName;
$deleteKey;
$created;
$modified;


//受け取ったidパラメータを取得
$threadsId = filter_input(INPUT_GET, 'id');

//SQLでidパラメータと対応するスレッドテーブルのidを取得
if(!empty($threadsId) ) {
    
    $threadPdo = new DbUtil();
    $threadsData = $threadPdo->getThredsData($threadsId);
//    $commentsData = $threadPdo->getCommentsData($threadsId);  
    
   
        foreach ($threadsData as $row) {
            $id = $row['threads_id'];
            $threadsName = $row['threads_name'];
//            $deleteKey = $row['delete_key'];
//            $created = $row['created'];
//            $modified = $row['modified'];
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
                width: 750px;
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
            
            .submit02{
                display: block;
                width: 90px;
                height: 40px;
                margin: 0 auto;
            }
            
            [name="delete"]{
                width: 90px;                           
            }
            
            form{
                padding-top: 30px;
            }
            
           
        </style>
    </head>
    <body>
        <div id="wraper">
        <h1><?php echo $threadsName; ?></h1>
        
        <?php
        
            $num = 1;
            foreach ($threadsData as $row2) {      
            echo '<form action="comment.php" method="post">';
            echo '<div class="toukou_area">';
            echo "<p class=\"toukousya\">No".$num."&nbsp;&nbsp;投稿者：".$row2['nickname']."&nbsp;&nbsp;ID：".$row2['unique_id']."&nbsp;&nbsp"."投稿日：".$row2['created']."</p>";
            echo "<p class=\"txt_bold\">".$row2['comment']."</p>";
            echo "<p class=\"toukoubi\"><input type=\"hidden\" name=\"comments_id\" value=\"".$row2['comments_id']."\"><input type=\"text\" name=\"delete_key\"><input type=\"hidden\" value=\"".$id."\"name=\"threads_id\"><input type=\"submit\" name=\"submit\" value=\"削除\"></p>";
            echo '</div></form>';
            $num++;
        }
        ?>
        
        <form action="comment.php" method="post">
            <dl>
                <dt>ニックネーム</dt>
                <dd><input type="text" name="name"></dd>
                <dt>コメント</dt>
                <dd><textarea name="comment"></textarea></dd>
                <dt>削除キー</dt>
                <dd><input type="text" name="delete_key" ></dd>
            </dl>
            <input type="hidden" value="<?php echo $threadsId ?>" name="threads_id">
            <input type="hidden" value="<?php echo $threadsId ?>" name="unique_id">
            <input type="hidden" value="<?php echo $threadsId ?>" name="created">
            <input type="submit" value="投稿" name="submit" class="submit02">
        </from>
        </div>
    </body>
</html>
