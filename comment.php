<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$dsn = 'mysql:dbname=a-team-2ch;host=localhost';
$user = 'root';
$password = '';

$name = filter_input(INPUT_POST,"name");
$thred_id = filter_input(INPUT_POST,"thread_id");
$comment = htmlspecialchars(filter_input(INPUT_POST,"comment"));
try{
    $pdo = new PDO($dsn,$user,$password);
    $pdo->query('SET NAMES utf8');
    echo "投稿が完了しました";
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
}catch(PDOException $e){
    echo('Error:'.$e->getMessage());
    die();
} 
try{
    $pdo->beginTransaction();
    $sql ="INSERT INTO comments(threads_id,comment,nickname)VALUES(:thredsIdDate,:commentDate,:niknameDate)"; 
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':thredsIdDate',$_POST['threads_id']); 
    $stmh->bindValue(':niknameDate',$_POST['name']); 
    $stmh->bindValue(':commentDate',$comment); 
    $stmh->execute();
    $pdo->commit();
}catch(PDOException $e){
    $pdo->rollBack();
    echo('Error:'.$e->getMessage());
}
$postPage = filter_input(INPUT_SERVER,'HTTP_REFERER');
header("LOCATION: $postPage");