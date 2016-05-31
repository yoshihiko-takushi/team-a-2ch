<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./_util/DbUtil.php');

$nickName = filter_input(INPUT_POST, "name");
$threadsId = filter_input(INPUT_POST, "threads_id");
$comment = htmlspecialchars(filter_input(INPUT_POST, "comment"));
//$uniqueId = filter_input(INPUT_POST,"unique_id");
$deleteKey = filter_input(INPUT_POST, "delete_key");
$commentsId = filter_input(INPUT_POST, "comments_id");
$created = date("Y/m/d H:i:s");
$submitType = filter_input(INPUT_POST, 'submit');
$postPage = filter_input(INPUT_SERVER, 'HTTP_REFERER');

$dbu = new DbUtil();
if ($dbu === false) {
    die('不具合が発生しました');
}

if ($submitType === '投稿') {
    $dbu->insertComment($threadsId, $nickName, $comment, $deleteKey, $created);
    header("LOCATION: $postPage");
}

if ($submitType === '削除') {
    $keyData = $dbu->deleteComment($threadsId, $commentsId, $deleteKey);
    header("LOCATION: $postPage");
}