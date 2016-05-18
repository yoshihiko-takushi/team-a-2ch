<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./_util/DbUtil.php');

$nickName = filter_input(INPUT_POST,"name");
$threadsId = filter_input(INPUT_POST,"threads_id");
$comment = htmlspecialchars(filter_input(INPUT_POST,"comment"));

$dbu = new DbUtil(); 
if($dbu === false){
    die('不具合が発生しました');
}
$dbu->insertComment($threadsId, $nickName, $comment);

        
$postPage = filter_input(INPUT_SERVER,'HTTP_REFERER');
header("LOCATION: $postPage");