<?php
$dsn = 'mysql:dbname=a-team-2ch;host=localhost';
$user = 'root';
$password = '';
$id;
$threads_name;
$delete_key;
$created;
$modified;

//受け取ったidパラメータを取得
$threads_id = filter_input(INPUT_GET, 'id');
//SQLでidパラメータと対応するスレッドテーブルのidを取得
if(empty($threads_id) ) {
    try{
        $dbh = new PDO($dsn, $user, $password);
        echo ('接続に成功しました。<br>');
        $dbh->query('SET NAMES utf8');
        $sql = "select * from threads WHERE $threads_id";
        foreach ($dbh->query($sql) as $row) {
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
    </head>
    <body>
        <h1><?php echo $threads_name?></h1>
        <p>作成者：</p>
        
        
        
        <ul>

        </ul>

    </body>
</html>
