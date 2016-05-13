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

try{
    $pdo = new PDO($dsn,$user,$password);
    $pdo->query('SET NAMES utf8');
    echo "データベースに接続しました";
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
}catch(PDOException $e){
    echo('Error:'.$e->getMessage());
    die();
} 
try{
    $pdo->beginTransaction();
    $sql ="INSERT INTO comments()VALUES()"; 
}catch(PDOException $e){
    
}
